<?php
namespace Bladex;

use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;



class BladeRenderer
{
    private $blade;
    private $viewsPath;
    private $cachePath;

    public function __construct()
    {
        $this->viewsPath = $_SERVER['DOCUMENT_ROOT'] . '/local/views';
        $this->cachePath = $_SERVER['DOCUMENT_ROOT'] . '/local/cache/blade';

        $this->initializeBlade();
    }

    private function initializeBlade()
    {
        $filesystem = new Filesystem();

        // Создаем директорию для кэша если её нет
        if (!$filesystem->exists($this->cachePath)) {
            $filesystem->makeDirectory($this->cachePath, 0755, true);
        }

        // Создаем Blade компилятор
        $bladeCompiler = new BladeCompiler($filesystem, $this->cachePath);

        // Регистрируем Bitrix директивы
        $this->registerBitrixDirectives($bladeCompiler);

        // Создаем движок
        $resolver = new EngineResolver();
        $resolver->register('blade', function () use ($bladeCompiler) {
            return new CompilerEngine($bladeCompiler);
        });

        // Создаем поисковик шаблонов
        $finder = new FileViewFinder($filesystem, [$this->viewsPath]);
        $finder->addExtension('blade.php');

        // Создаем фабрику представлений
        $this->blade = new Factory($resolver, $finder, new \Illuminate\Events\Dispatcher());
    }

    private function registerBitrixDirectives(BladeCompiler $compiler)
    {
        // Директива для подключения CSS
        $compiler->directive('css', function ($expression) {
            return "<?php \$GLOBALS['APPLICATION']->AddHeadString('<link rel=\"stylesheet\" type=\"text/css\" href=\"' . CUtil::GetAdditionalFileURL({$expression}) . '\" />'); ?>";
        });

        // Директива для подключения JS
        $compiler->directive('js', function ($expression) {
            return "<?php \$GLOBALS['APPLICATION']->AddHeadString('<script src=\"' . CUtil::GetAdditionalFileURL({$expression}) . '\"></script>'); ?>";
        });

        // Директива для вывода заголовка страницы
        $compiler->directive('showHead', function () {
            return "<?php \$GLOBALS['APPLICATION']->ShowHead(); ?>";
        });

        // Директива для показа панели администратора
        $compiler->directive('showPanel', function () {
            return "<?php \$GLOBALS['APPLICATION']->ShowPanel(); ?>";
        });

        // Директива для вывода title
        $compiler->directive('showTitle', function ($expression = null) {
            if ($expression) {
                return "<?php \$GLOBALS['APPLICATION']->ShowTitle({$expression}); ?>";
            }
            return "<?php \$GLOBALS['APPLICATION']->ShowTitle(); ?>";
        });

        // Директива для установки title
        $compiler->directive('setTitle', function ($expression) {
            return "<?php \$GLOBALS['APPLICATION']->SetTitle({$expression}); ?>";
        });

        // Директива для подключения компонентов Bitrix
        $compiler->directive('component', function ($expression) {
            return "<?php \$GLOBALS['APPLICATION']->IncludeComponent({$expression}); ?>";
        });

        // Директива для проверки прав доступа
        $compiler->directive('ifAdmin', function () {
            return "<?php if(\$GLOBALS['USER']->IsAdmin()): ?>";
        });

        $compiler->directive('endifAdmin', function () {
            return "<?php endif; ?>";
        });

        // Директива для авторизованных пользователей
        $compiler->directive('ifAuth', function () {
            return "<?php if(\$GLOBALS['USER']->IsAuthorized()): ?>";
        });

        $compiler->directive('endifAuth', function () {
            return "<?php endif; ?>";
        });
    }

    public function render($view, $data = [])
    {
        // Добавляем глобальные переменные Bitrix в данные шаблона
        $data = array_merge($data, [
            'APPLICATION' => $GLOBALS['APPLICATION'],
            'USER' => $GLOBALS['USER'],
            'DB' => $GLOBALS['DB']
        ]);

        return $this->blade->make($view, $data)->render();
    }

    public function share($key, $value = null)
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->blade->share($k, $v);
            }
        } else {
            $this->blade->share($key, $value);
        }
    }
}