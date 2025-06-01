<?php
namespace App\Controllers;

use App\Services\DataBaseService;
use Bladex\Inject;
class HomeController extends BaseController
{
    #[Inject]
    protected DataBaseService $data;

    protected function getDefaultPreFilters(): array
    {
        return [];
    }
    public function indexAction()
    {
        var_dump($this->data);
        die();
        return view('home.index', []);
    }
}