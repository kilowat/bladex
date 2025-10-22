<?php
namespace Controllers;

use App\Repositories\CatalogRepository;
use Bladex\Pagination;


class ShopController extends BaseController
{
    public function indexAction(CatalogRepository $catalogRepository, $section = null)
    {
        $recordsCount = count(getFixture('products'));
        $pagination = Pagination::initFromUri($recordsCount);
        $products = $catalogRepository->getProducts(
            limit: $pagination->getLimit(),
            offset: $pagination->getOffset()
        );

        return view('pages.shop.index')->with(
            [
                'products' => $products,
                'pagination' => $pagination,
            ]
        );
    }
}