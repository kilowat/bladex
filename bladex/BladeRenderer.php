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
use Bitrix\Main\Application;
class BladeRenderer
{
    private static ?self $instance = null;
    private readonly string $viewsPath;
    private readonly string $cachePath;
    private readonly array $directivesPath;

    private ?Factory $viewFactory = null;
    private ?BladeCompiler $compiler = null;
    private bool $isBooted = false;
    private string $baseDir;

    private function __construct()
    {
        $config = $this->getConfiguration();
        $this->baseDir = useBaseDir();

        $this->viewsPath = $config['views_path'] ?? $this->baseDir . '/views';
        $this->cachePath = $config['cache_path'] ?? $this->baseDir . '/cache/blade';

        $bladeDirectives = $this->baseDir . '/bladex/directives.php';
        $customDirectives = $this->baseDir . '/config/directives.php';
        $this->directivesPath = [$bladeDirectives, $customDirectives];

        $this->boot();
    }

    public static function getInstance(): self
    {
        return self::$instance ??= new self();
    }

    public function make(string $view): View
    {
        return new View($this, $view);
    }

    public function getResponse(string $view, array $data = []): HttpResponse
    {

        try {
            $html = $this->viewFactory->make($view, $data)->render();
            $response = Application::getInstance()->getContext()->getResponse();
            return $response
                ->setContent($html)
                ->addHeader('Content-Type', 'text/html; charset=UTF-8');
        } catch (\Illuminate\View\ViewException $e) {
            throw $e->getPrevious() ?? $e; // пробрасываем оригинальное исключение
        }
    }

    public function getHtml(string $view, array $data = []): string
    {
        return $this->viewFactory->make($view, $data)->render();
    }
    public function exists(string $view): bool
    {
        return $this->viewFactory->exists($view);
    }

    public function share(string|array $key, mixed $value = null): self
    {
        $this->viewFactory->share($key, $value);
        return $this;
    }

    public function getViewFactory(): Factory
    {
        return $this->viewFactory;
    }

    public function getCompiler(): BladeCompiler
    {
        return $this->compiler;
    }

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


    public function directive(string $name, callable $callback): self
    {
        $this->compiler->directive($name, $callback);
        return $this;
    }

    private function boot(): void
    {
        if ($this->isBooted) {
            return;
        }

        $filesystem = new Filesystem();

        $this->ensureDirectoryExists($this->cachePath, $filesystem);
        $this->ensureDirectoryExists($this->viewsPath, $filesystem);

        $this->compiler = new BladeCompiler($filesystem, $this->cachePath);

        $this->loadCustomDirectives();

        $resolver = new EngineResolver();
        $resolver->register('blade', fn() => new CompilerEngine($this->compiler));

        $finder = new FileViewFinder($filesystem, [$this->viewsPath]);
        $finder->addExtension('blade.php');

        $this->viewFactory = new Factory($resolver, $finder, new Dispatcher());

        $this->isBooted = true;
    }

    private function loadCustomDirectives(): void
    {
        foreach ($this->directivesPath as $path) {
            if (!file_exists($path)) {
                continue;
            }

            $directives = require $path;

            if (!is_array($directives)) {
                continue;
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

    private function ensureDirectoryExists(string $path, Filesystem $filesystem): void
    {
        if (!$filesystem->exists($path)) {
            $filesystem->makeDirectory($path, 0755, true);
        }
    }

    private function getConfiguration(): array
    {
        return useConfig('blade');
    }
}
