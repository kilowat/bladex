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
        return "<?php echo \\Bitrix\\Main\\Page\\Asset::getInstance()->getCss(); ?>";
    },

    // Вывести все скрипты
    'scripts' => function () {
        return "<?php echo \\Bitrix\\Main\\Page\\Asset::getInstance()->getJs(); ?>";
    },

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

    // Только стили и скрипты текущего шаблона
    'templateAssets' => function () {
        return "<?php
        (function () {
            \$template = SITE_TEMPLATE_ID;
            \$asset = \\Bitrix\\Main\\Page\\Asset::getInstance();
            \$r = new ReflectionObject(\$asset);

            foreach (['css', 'js'] as \$type) {
                \$prop = \$r->getProperty(\$type);
                \$prop->setAccessible(true);
                \$list = \$prop->getValue(\$asset);

                \$filtered = array_filter(\$list, fn(\$p) =>
                    is_string(\$p) && (str_contains(\$p, \"/{\$template}/\") || str_starts_with(\$p, '/local/'))
                );

                \$prop->setValue(\$asset, \$filtered);
            }

            echo \$asset->getCss();
            echo \$asset->getJs();
        })();
    ?>";
    },

    'templateScripts' => function () {
        return "<?php
            (function () {
                \$template = SITE_TEMPLATE_ID;
                \$asset = \\Bitrix\\Main\\Page\\Asset::getInstance();
                \$r = new ReflectionObject(\$asset);

                \$jsProp = \$r->getProperty('js');
                \$jsProp->setAccessible(true);
                \$jsList = \$jsProp->getValue(\$asset);

                \$filtered = array_filter(\$jsList, fn(\$p) =>
                    str_contains(\$p, \"/{\$template}/\") || str_starts_with(\$p, '/local/')
                );

                \$jsProp->setValue(\$asset, \$filtered);

                echo \$asset->showBodyScripts();
            })();
        ?>";
    },

    // Или через callback функцию для более сложной логики
    function ($compiler) {
        $compiler->directive('csrf', function () {
            return "<?php echo bitrix_sessid_post(); ?>";
        });
    }
];