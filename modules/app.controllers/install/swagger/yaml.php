<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include ($_SERVER['DOCUMENT_ROOT'].'/local/api/vendor/autoload.php');

$openapi = \OpenApi\Generator::scan([
    __DIR__.'/../local/api/src',
    ]);


header('Content-Type: application/x-yaml');
header('Content-disposition: attachment;filename="schema.yaml"');
echo $openapi->toYaml();
