<?php

namespace Widgets;

use App\Services\DataBaseService;
use Bitrix\Main\HttpResponse;
use Bladex\Widget;

class LastNews extends Widget
{
    public function __construct(protected DataBaseService $dataBaseService, )
    {
    }

    public function run(): HttpResponse
    {
        $news = [
            ['title' => 'Test'],
        ];
        return view('last_news', ['items' => $news]);
    }
}