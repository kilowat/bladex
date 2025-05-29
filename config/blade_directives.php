<?php
return [
    // Директива для вывода даты в российском формате
    'rudate' => function ($expression) {
        return "<?php echo date('d.m.Y', strtotime($expression)); ?>";
    },

    // Директива для проверки авторизации в Bitrix
    'auth' => function () {
        return "<?php if (\\Bitrix\\Main\\Engine\\CurrentUser::get()->isAuthorized()): ?>";
    },

    'endauth' => function () {
        return "<?php endif; ?>";
    },

    // Директива для подключения компонентов Bitrix
    'component' => function ($expression) {
        return "<?php \$APPLICATION->IncludeComponent($expression); ?>";
    },

    // Или через callback функцию для более сложной логики
    function ($compiler) {
        $compiler->directive('csrf', function () {
            return "<?php echo bitrix_sessid_post(); ?>";
        });
    }
];