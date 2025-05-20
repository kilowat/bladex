<?php

namespace App;

use Jenssegers\Blade\Blade;

class BladeRenderer
{
    private static $instance;
    private $blade;

    private function __construct()
    {
        $viewsPath = $_SERVER['DOCUMENT_ROOT'] . '/app/Views';
        $cachePath = $_SERVER['DOCUMENT_ROOT'] . '/app/Views/cache';

        if (!file_exists($cachePath)) {
            mkdir($cachePath, 0777, true);
        }

        $this->blade = new Blade($viewsPath, $cachePath);
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function render($view, $data = [])
    {
        return $this->blade->render($view, $data);
    }
}