<?php

namespace App\Widgets;

use App\Services\DataBaseService;
use Bitrix\Main\HttpResponse;
use Bladex\Widget;

class LastNews extends Widget
{
    protected array $config = [
        'limit' => 10
    ];
    public function __construct(protected DataBaseService $dataBaseService, )
    {
    }

    public function run(): HttpResponse
    {
        $news = [
            ['title' => $this->config['limit']],
        ];
        return useView('last_news', ['items' => $news]);
    }
}