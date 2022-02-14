<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'name' => '1xSMART',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'sourceLanguage' => 'en',
    'language' => 'en', //'en-US',
    'modules' => [
        'tinkoff' => [
            'class' => 'frontend\modules\tinkoff\Api',
        ],
    ],
    'components' => [
        //************************************************************************
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        //************************************************************************
        'user' => [
            'identityClass'   => 'common\models\Users',
            'enableAutoLogin' => true,
            'enableSession'   => true,
            'identityCookie'  => ['name' => '_identity-frontend', 'httpOnly' => true],
            'on ' . \yii\web\User::EVENT_AFTER_LOGIN => ['common\models\Users', 'afterLogin'],
        ],
        //************************************************************************
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        //************************************************************************
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        //************************************************************************
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        //************************************************************************
        'urlManager' => [
            //https://elisdn.ru/blog/39/yii-based-multilanguage-site-interface-and-urls
            //https://github.com/codemix/yii2-localeurls
            'class' => 'codemix\localeurls\UrlManager',
            'languages' => ['en', 'ru', 'ua'],
            'languageCookieDuration' => 2592000,
            //'languages' => ['en'],
            'enableDefaultLanguageUrlCode' => true,
            'enableLanguagePersistence' => true,
            'enableLanguageDetection' => true,
            'ignoreLanguageUrlPatterns' => [
                // route pattern => url pattern
                '#^user/upload-profile-photo#' => '#^user/upload-profile-photo#',
                '#^user/upload-profile-video#' => '#^user/upload-profile-video#',
                '#^tinkoff/*#' => '#^tinkoff/*#',
                '#^sounds/*#' => '#^sounds/*#',
                // Добавить исключения для модулей пейпал, криптонатор и др.
            ],

            //'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                ['pattern' => 'api/upload-video-lessons', 'route' => 'api/default/upload-video-lessons'],
                ['pattern' => 'api',          'route' => 'api/default/index'],
                ['pattern' => 'api/<action>', 'route' => 'api/default/index'],

                ['pattern' => 'tinkoff',          'route' => 'tinkoff/default/index'],
                ['pattern' => 'tinkoff/<action>', 'route' => 'tinkoff/default/index'],

                [
                    'pattern' => '<action:' . implode('|', $_SITE_ACTION_PAGES) . '>/<id:\w*>',
                    'route' => 'site/<action>', 'defaults' => ['id' => null]
                ],

                // Все остальное отправляем на контроллер SiteController акшен static
                [
                    //'pattern' => '<action:show-logo|vocal-course|learning-stages|cost|for-coaches|contacts|find-tutors|disciplines|become-tutor|support>/<id:\w*>',
                    //'pattern' => '<action:[\w\-]+>/<id:\w*>',
                    'pattern' => '<action:' . implode('|', $_STATIC_PAGES) . '>/<id:\w*>',
                    'route' => 'site/static', 'defaults' => ['id' => null]
                ],
            ],
        ],
        //************************************************************************
        'view' => [
            'theme' => [
                'basePath' => '@app/themes/' . DESIGN_THEME,
                'baseUrl' => '@web/themes/' . DESIGN_THEME,
                'pathMap' => [
                    '@app/views'   => [
                        '@app/themes/holidays',        //Сначала будет искать файлы виевов тут, и если их нет то
                        '@app/themes/' . DESIGN_THEME, // тогда уже тут, таким образом можно подменять на праздники основной виев на праздничный
                    ],
                    //'@app/views/layouts' => '@app/themes/' . DESIGN_THEME . '/layouts',
                    '@app/modules'       => '@app/themes/' . DESIGN_THEME . '/modules',
                    '@app/widgets'       => '@app/themes/' . DESIGN_THEME . '/widgets',
                    '@app/page'          => '@app/themes/' . DESIGN_THEME . '/page',
                ],
            ],
        ],
        //************************************************************************
        'i18n' => [
            'translations' => [
                'yii' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    'sourceLanguage' => 'en-US',
                ],
                'models*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    'sourceLanguage' => 'en-US',
                ],
                'mail*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages/' . DESIGN_THEME,
                    'sourceLanguage' => 'en-US',
                ],
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages/' . DESIGN_THEME,
                    'sourceLanguage' => 'en-US',
                ],
                'static*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages/' . DESIGN_THEME,
                    'sourceLanguage' => 'en-US',
                ],
                'modals*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages/' . DESIGN_THEME,
                    'sourceLanguage' => 'en-US',
                ],
                'student*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages/' . DESIGN_THEME,
                    'sourceLanguage' => 'en-US',
                ],
                'teacher*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages/' . DESIGN_THEME,
                    'sourceLanguage' => 'en-US',
                ],
                'controllers*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages/' . DESIGN_THEME,
                    'sourceLanguage' => 'en-US',
                ],
            ],
        ],
        //************************************************************************
        'geoIp' => [
            'class' => 'scorpsan\geoip\GeoIp',
            // uncomment next line if you register on sypexgeo.net and paste your key
            //'keySypex' => 'key-sypexgeo-net-this',
        ],
    ],
    'params' => $params,
];
