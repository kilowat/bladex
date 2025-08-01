<?php

namespace Widgets;
use Bladex\View;
use Bladex\Widget;
use Spatie\Menu\Menu;
use Spatie\Menu\Link;

/**
 * Пример вызова
 * @widget('LastNews')
 * {!! Widgets\LastNews::make()->render() !!}
 */

class Breadcrumbs extends Widget
{

    public function render(): View|string
    {
        $breadcrumbs = useContainer()->get('Breadcrumbs');
        $res = $breadcrumbs->generate()->get();
        return useView('ui.breadcrumbs');

    }
}