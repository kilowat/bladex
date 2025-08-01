<?php

use DI\Container;

return [
    'Breadcrumbs' => function (Container $c) {
        return new Widgets\Breadcrumbs\Builder();
    },
];