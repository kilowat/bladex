<?php
namespace Controllers;

use App\Repositories\CatalogRepository;
use Bladex\Pagination;


class ShopController extends BaseController
{
    public function indexAction(CatalogRepository $catalogRepository)
    {

        $pagination = new Pagination();
        $pagination->initFromUri();
        $recordsCount = count(useFixture('products'));
        $pagination->setRecordCount($recordsCount);
        $pagination->setPageSize(9);
        $products = $catalogRepository->getProducts(
            limit: $pagination->getLimit(),
            offset: $pagination->getOffset()
        );

        $navData = $pagination->getNavigationData();

        return useView('pages.shop.index')->with(
            [
                'products' => $products,
                'navData' => $navData,
            ]
        );
    }
}