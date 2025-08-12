<?php
namespace Controllers;

use App\Repositories\CatalogRepository;
use Bitrix\Main\Page\Frame;
use Bitrix\Main\Web\Response\HtmlResponse;
use Bitrix\Main\Context;
use Bitrix\Main\Composite\Engine;


class ShopController extends BaseController
{
    public function indexAction(CatalogRepository $catalogRepository)
    {
        $products = $catalogRepository->getList();

        return useView('pages.shop.index')->with('products', $products);
    }
}