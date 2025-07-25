<?php

namespace Widgets;

use App\Exceptions\AppError;
use App\Exceptions\AppException;
use App\Services\DataBaseService;
use Bladex\View;
use Bladex\Widget;
use Exception;

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