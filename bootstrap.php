<?php
require_once __DIR__ . '/bladex/func.php';

$composerJsonPath = __DIR__ . '/composer.json';
$vendorDir = '/vendor'; 

if (file_exists($composerJsonPath)) {
    $composerData = json_decode(file_get_contents($composerJsonPath), true);
    if (
        isset($composerData['config']) &&
        isset($composerData['config']['vendor-dir'])
    ) {
        $vendorDir = '/' . trim($composerData['config']['vendor-dir'], '/');
    }
}

$vendorPath = realpath(__DIR__ . $vendorDir);

if (!$vendorPath || !file_exists($vendorPath . '/autoload.php')) {
    throw new RuntimeException("Не найден autoload.php в: $vendorPath");
}

require_once $vendorPath . '/autoload.php';