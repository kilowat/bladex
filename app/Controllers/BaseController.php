<?php
namespace App\Controllers;

use Bitrix\Main\Engine\Controller;
use Bladex\BladeRenderer;
use Bitrix\Main\HttpResponse;

abstract class BaseController extends Controller
{
    protected $blade;

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
        $html = $this->blade->render($view, $data);

        // Используем специальный HtmlResponse
        return (new HttpResponse())->setContent($html);
    }
}
