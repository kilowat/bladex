<?php

namespace Bladex;

use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;
use Illuminate\Events\Dispatcher;
use Bitrix\Main\HttpResponse;
use Bitrix\Main\Config\Configuration;

class BladeRenderer
{
    private static ?self $instance = null;

    private readonly string $viewsPath;
    private readonly string $cachePath;
    private readonly array $directivesPath;

    private ?Factory $viewFactory = null;
    private ?BladeCompiler $compiler = null;
    private bool $isBooted = false;
    private $baseDir;

    private function __construct()
    {

        $config = $this->getConfiguration();
        $this->baseDir = useBaseDir();

        $this->viewsPath = $config['views_path'] ?? $this->baseDir . '/views';
        $this->cachePath = $config['cache_path'] ?? $this->baseDir . '/cache/blade';
        $bladeDirectives = $this->baseDir . '/bladex/directives.php';
        $customDirectives = $this->baseDir . '/config/directives.php';
        $this->directivesPath = [$bladeDirectives, $customDirectives];

        // Инициализируем сразу при создании экземпляра
        $this->boot();
    }

    public static function getInstance(): self
    {
        return self::$instance ??= new self();
    }

    /**
     * Инициализация Blade компонентов
     */
    private function boot(): void
    {
        if ($this->isBooted) {
            return;
        }

        $filesystem = new Filesystem();

        // Создаем директории если не существуют
        $this->ensureDirectoryExists($this->cachePath, $filesystem);
        $this->ensureDirectoryExists($this->viewsPath, $filesystem);

        // Инициализируем компилятор
        $this->compiler = new BladeCompiler($filesystem, $this->cachePath);

        // Загружаем пользовательские директивы
        $this->loadCustomDirectives();

        // Настраиваем движок рендеринга
        $resolver = new EngineResolver();
        $resolver->register('blade', fn() => new CompilerEngine($this->compiler));

        // Настраиваем поиск шаблонов
        $finder = new FileViewFinder($filesystem, [$this->viewsPath]);
        $finder->addExtension('blade.php');

        // Создаем фабрику представлений
        $this->viewFactory = new Factory($resolver, $finder, new Dispatcher());

        $this->isBooted = true;
    }

    /**
     * Рендеринг шаблона с возвращением HttpResponse
     */
    public function render(string $view, array $data = []): HttpResponse
    {
        try {
            $html = $this->viewFactory->make($view, $data)->render();

            return (new HttpResponse())
                ->setContent($html)
                ->addHeader('Content-Type', 'text/html; charset=UTF-8');

        } catch (\Exception $e) {
            // Логируем ошибку в журнал Bitrix
            \Bitrix\Main\Diag\Debug::writeToFile([
                'message' => 'Blade render error',
                'view' => $view,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], '', '/local/logs/blade_errors.log');

            // В режиме разработки показываем детальную ошибку
            if ($this->isDebugMode()) {
                return (new HttpResponse())
                    ->setContent($this->renderErrorPage($e, $view, $data))
                    ->setStatus(500);
            }

            throw $e;
        }
    }

    /**
     * Получение HTML строки без HttpResponse
     */
    public function renderToString(string $view, array $data = []): string
    {
        return $this->viewFactory->make($view, $data)->render();
    }

    /**
     * Проверка существования шаблона
     */
    public function exists(string $view): bool
    {
        return $this->viewFactory->exists($view);
    }

    /**
     * Добавление глобальных данных для всех шаблонов
     */
    public function share(string|array $key, mixed $value = null): void
    {
        $this->viewFactory->share($key, $value);
    }

    /**
     * Получение фабрики представлений
     */
    public function getViewFactory(): Factory
    {
        return $this->viewFactory;
    }

    /**
     * Получение компилятора Blade
     */
    public function getCompiler(): BladeCompiler
    {
        return $this->compiler;
    }

    /**
     * Очистка кеша шаблонов
     */
    public function clearCache(): bool
    {
        $filesystem = new Filesystem();

        if (!$filesystem->exists($this->cachePath)) {
            return true;
        }

        try {
            $filesystem->cleanDirectory($this->cachePath);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Загрузка пользовательских директив
     */
    private function loadCustomDirectives(): void
    {
        foreach ($this->directivesPath as $path) {

            if (!file_exists($path)) {
                return;
            }

            $directives = require $path;

            if (!is_array($directives)) {
                return;
            }

            foreach ($directives as $name => $callback) {

                if (is_string($name) && is_callable($callback)) {
                    $this->compiler->directive($name, $callback);
                } elseif (is_callable($callback)) {
                    $callback($this->compiler);
                }
            }
        }
    }

    /**
     * Обеспечение существования директории
     */
    private function ensureDirectoryExists(string $path, Filesystem $filesystem): void
    {
        if (!$filesystem->exists($path)) {
            $filesystem->makeDirectory($path, 0755, true);
        }
    }

    /**
     * Получение конфигурации
     */
    private function getConfiguration(): array
    {
        $configPath = $this->baseDir . '/config/blade.php';

        if (file_exists($configPath)) {
            return include $configPath;
        }

        return [];
    }

    /**
     * Проверка режима отладки
     */
    private function isDebugMode(): bool
    {
        return Configuration::getValue('exception_handling')['debug'] === true;
    }

    /**
     * Рендеринг страницы ошибки для режима разработки
     */
    private function renderErrorPage(\Exception $e, string $view, array $data): string
    {
        return sprintf(
            '<div style="padding: 20px; font-family: monospace; background: #f8f8f8; border: 1px solid #ddd;">
                <h2 style="color: #d32f2f;">Blade Template Error</h2>
                <p><strong>View:</strong> %s</p>
                <p><strong>Message:</strong> %s</p>
                <p><strong>File:</strong> %s:%d</p>
                <details>
                    <summary>Stack Trace</summary>
                    <pre>%s</pre>
                </details>
                <details>
                    <summary>Template Data</summary>
                    <pre>%s</pre>
                </details>
            </div>',
            htmlspecialchars($view),
            htmlspecialchars($e->getMessage()),
            htmlspecialchars($e->getFile()),
            $e->getLine(),
            htmlspecialchars($e->getTraceAsString()),
            htmlspecialchars(print_r($data, true))
        );
    }
}
