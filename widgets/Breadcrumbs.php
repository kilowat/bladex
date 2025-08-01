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
        $items = \Bladex\Breadcrumbs::generate(
            useCurrentRoute()->getOptions()->getFullName(),
        )->get();

        return useView('ui.breadcrumbs')->with('items', $items);
    }
}