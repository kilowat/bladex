<?php

namespace Widgets;
use Bladex\View;
use Bladex\Widget;

class Breadcrumbs extends Widget
{
    public function render(): View|string
    {
        $items = \Bladex\Breadcrumbs::generate(
            getCurrentRoute()->getOptions()->getFullName(),
        )->get();

        return view('ui.breadcrumbs')->with('items', $items);
    }
}