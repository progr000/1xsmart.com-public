<?php
defined('CREATE_ABSOLUTE_URL') or define('CREATE_ABSOLUTE_URL', true);
defined('SQL_DATE_FORMAT') or define('SQL_DATE_FORMAT', "Y-m-d H:i:s");
defined('PJAX_TIMEOUT') or define('PJAX_TIMEOUT', 3000);
defined('CACHE_TTL') or define('CACHE_TTL', 3600);

return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',

    'timeZone' => 'UTC',

    'components' => [

    ],
    'modules' => [
        'gridview' =>  [
            'class' => '\kartik\grid\Module',
        ],
    ],
];
