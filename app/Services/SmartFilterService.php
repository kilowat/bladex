<?php
namespace App\Services;

class SmartFilterService
{
    private $cbSmartFilter;
    private $iblockId;
    private $arParams;

    public function __construct(
        $iblockId,
    ) {
        \CBitrixComponent::includeComponentClass('bitrix:catalog.smart.filter');
        $this->cbSmartFilter = new \CBitrixCatalogSmartFilter();
        $this->iblockId = $iblockId;
    }

    public function setParams($customParams)
    {
        $this->arParams = array_merge($this->getParams(), $customParams);
        return $this;
    }
    public function apply(&$filter, ?string $sectionCode = null, ?array $prefilter = null): static
    {
        if (empty($_REQUEST['del_filter']) && empty($_REQUEST['set_filter']))
            return $this;

        $this->exec($sectionCode, $prefilter);
        global ${$this->arParams['FILTER_NAME']};

        $filter = array_merge(${$this->arParams['FILTER_NAME']}, $filter);
        return $this;
    }

    private function exec($sectionCode, $prefilter)
    {
        $this->init();

        $params = $this->getParams();

        if ($sectionCode != null)
            $params['SECTION_CODE'] = $sectionCode;

        if ($prefilter != null) {
            global ${$this->arParams['PREFILTER_NAME']};
            ${$this->arParams['PREFILTER_NAME']} = $prefilter;
        }

        $this->cbSmartFilter->arParams = $params;
        $this->cbSmartFilter->executeComponent();
    }

    private function init()
    {
        $this->cbSmartFilter->initComponent('bitrix:catalog.smart.filter');
    }

    public function getResult(?string $sectionCode = null, ?array $prefilter = null)
    {
        $this->exec($sectionCode, $prefilter);
        return $this->cbSmartFilter->arResult;
    }

    private function getParams()
    {
        return [
            "CACHE_GROUPS" => "Y",
            "CACHE_TIME" => "36000000",
            "CACHE_TYPE" => "A",
            "COMPOSITE_FRAME_MODE" => "A",
            "COMPOSITE_FRAME_TYPE" => "AUTO",
            "CONVERT_CURRENCY" => "N",
            "DISPLAY_ELEMENT_COUNT" => "Y",
            "PREFILTER_NAME" => "smartPreFilter",
            "FILTER_NAME" => "arrFilter",
            "FILTER_VIEW_MODE" => "vertical",
            "HIDE_NOT_AVAILABLE" => "N",
            "IBLOCK_ID" => $this->iblockId,
            "PAGER_PARAMS_NAME" => "arrPager",
            "POPUP_POSITION" => "left",
            "SAVE_IN_SESSION" => "N",
            "SECTION_CODE" => '',
            "SECTION_CODE_PATH" => "",
            "SECTION_DESCRIPTION" => "-",
            "SECTION_TITLE" => "-",
            "SEF_MODE" => "N",
            "SEF_RULE" => "#SMART_FILTER_PATH#",
            "TEMPLATE_THEME" => "blue",
            "XML_EXPORT" => "N"
        ];
    }
}