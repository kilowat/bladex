<?php

namespace Widgets;
use Bladex\Pagination;
use Bladex\View;
use Bladex\Widget;


/**
 * Пример вызова
 * @widget('LastNews')
 * {!! Widgets\LastNews::make()->render() !!}
 */

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