<?php
namespace App\Controllers;

use Bitrix\Main\Engine\Controller;
use Bladex\BladeRenderer;
use Bladex\HtmlResponse;
use Bitrix\Main\HttpResponse;

abstract class BaseController extends Controller
{
    protected $blade;
    protected $data = [];

    public function __construct($request = null)
    {
        parent::__construct($request);
        $this->blade = new BladeRenderer();

    }
    protected function getDefaultPreFilters(): array
    {
        return [];
    }
    protected function render($view, $data = [])
    {
        $data = array_merge($this->data, $data);
        $html = $this->blade->render($view, $data);

        // Используем специальный HtmlResponse
        return (new HttpResponse())->setContent($html);
    }

    protected function assign($key, $value = null)
    {
        if (is_array($key)) {
            $this->data = array_merge($this->data, $key);
        } else {
            $this->data[$key] = $value;
        }
    }
}
