<?php
namespace Controllers;

use App\Repositories\CatalogRepository;
use Bladex\Pagination;


class ShopController extends BaseController
{
    public function indexAction(CatalogRepository $catalogRepository)
    {
        $recordsCount = count(useFixture('products'));
        $pagination = Pagination::initFromUri($recordsCount);
        $products = $catalogRepository->getProducts(
            limit: $pagination->getLimit(),
            offset: $pagination->getOffset()
        );

        return useView('pages.shop.index')->with(
            [
                'products' => $products,
                'pagination' => $pagination,
            ]
        );
    }
}