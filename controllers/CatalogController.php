<?php
namespace Controllers;
use App\Services\SmartFilterService;
use Bitrix\Iblock\PropertyIndex\Facet;
use Bitrix\Main\Loader;
/** @var CBitrixCatalogSmartFilter $cbSmartFilter */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

use Bitrix\Iblock\PropertyTable;
use Bitrix\Iblock\SectionPropertyTable;
use Bitrix\Iblock\PropertyIndex\Storage;

class CatalogController extends BaseController
{
    public function getFilterDataAction()
    {
        $smartFilter = new SmartFilterService(2);
        $filter = [];
        return $smartFilter->getResult();
    }
}