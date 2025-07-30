<?php

namespace Widgets;
use Bladex\View;
use Bladex\Widget;

/**
 * Пример вызова
 * {!! Widgets\LastNews::make()->render() !!}
 */

class LastNews extends Widget
{
    protected array $config = [
        'limit' => 10
    ];

    public function run(): View
    {
        $news = [
            ['limit' => $this->config['limit']],
        ];

        return useView('widgets.last_news')->with('items', $news);
    }
}