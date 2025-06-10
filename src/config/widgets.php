<?php

return [
    'last-news' => [
        'class' => \Widgets\LastNews::class,
        'configs' => [
            'default' => [
                'limit' => 5,
            ],
            'sidebar' => [
                'limit' => 3,
                'showDate' => false,
            ],
        ],
    ],
];

