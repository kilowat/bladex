<?php

use App\Controllers\BaseController;
use DI\ContainerBuilder;

$builder = new ContainerBuilder();
$container = $builder->build();

BaseController::setContainer($container);