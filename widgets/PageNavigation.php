<?php

namespace Widgets;
use Bladex\Pagination;
use Bladex\View;
use Bladex\Widget;

class PageNavigation extends Widget
{
    protected array $config = [
        'pagination' => null,
    ];

    public function render(): View|string
    {
        /**
         * @var Pagination
         */
        $pagination = $this->config['pagination'];
        if ($pagination->getPageCount() <= 1) {
            return '';
        }
        $navData = $pagination->getNavigationData();
        return useView('ui.pagination')->with('navData', $navData);
    }
}