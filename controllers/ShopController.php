<?php
namespace Controllers;

class ShopController extends BaseController
{
    public function indexAction()
    {
        return useView('pages.shop.index');
    }
}