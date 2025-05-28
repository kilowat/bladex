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

    public function render($view, $data = [])
    {
        return $this->blade->make($view, $data)->render();
    }

}