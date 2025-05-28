<?php

use Illuminate\View\Compilers\BladeCompiler;

return [
    function (BladeCompiler $blade) {
        $blade->directive('datetime', fn($expr) => "<?php echo ($expr)->format('d.m.Y H:i'); ?>");
    },
    function (BladeCompiler $blade) {
        $blade->directive('upper', fn($expr) => "<?php echo strtoupper($expr); ?>");
    },
];