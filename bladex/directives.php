<?php
return [
    // Подключить CSS
    'useCss' => function ($expression) {
        return "<?php useCss($expression); ?>";
    },

    'useJs' => function ($expression) {
        return "<?php useJs($expression); ?>";
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

    'component' => function ($expression) {
        return "<?php \$APPLICATION->IncludeComponent($expression); ?>";
    },

    'widget' => function ($expression) {
        return "<?php echo \\Bladex\\WidgetFactory::create({$expression})->render(); ?>";
    },
];
