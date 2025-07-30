<?php
namespace Controllers;

class ShopController extends BaseController
{
    public function indexAction()
    {
        return useView('shop.index');
    }
}