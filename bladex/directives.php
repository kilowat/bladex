<?php
return [
    // Подключить CSS
    'css' => function ($expression) {
        return "<?php \\Bitrix\\Main\\Page\\Asset::getInstance()->addCss('/local/assets/' . {$expression}); ?>";
    },

    // Подключить JS
    'js' => function ($expression) {
        return "<?php \\Bitrix\\Main\\Page\\Asset::getInstance()->addJs('/local/assets/' . {$expression}); ?>";
    },

    // Вывести все стили
    'styles' => function () {
        return "<?php echo \\Bitrix\\Main\\Page\\Asset::getInstance()->getCss(Bitrix\\Main\\Page\\AssetShowTargetType::TEMPLATE_PAGE); ?>";
    },

    // Вывести все скрипты
    'scripts' => function () {
        return "<?php echo \\Bitrix\\Main\\Page\\Asset::getInstance()->getJs(Bitrix\\Main\\Page\\AssetShowTargetType::TEMPLATE_PAGE); ?>";
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
    // Директива для проверки авторизации в Bitrix

];
