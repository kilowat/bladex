<?php

namespace Widgets;

use Services\DataBaseService;
use Bladex\View;
use Bladex\Widget;

class LastNews extends Widget
{
    protected array $config = [
        'limit' => 10
    ];
    public function __construct(protected DataBaseService $dataBaseService, )
    {
    }

    public function run(): View
    {
        $news = [
            ['title' => $this->config['limit']],
        ];

        return useView('last_news')->with('items', $news);
    }
}