<?php

namespace Widgets;
use Bladex\View;
use Bladex\Widget;

class LastNews extends Widget
{
    protected array $config = [
        'limit' => 10
    ];

    public function run(): View
    {
        $news = [
            ['title' => $this->config['limit']],
        ];

        return useView('last_news')->with('items', $news);
    }
}