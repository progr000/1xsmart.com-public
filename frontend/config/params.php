<?php
return [
    // Использовать сжатые или несжатые файлы стилей (css) и файлы яваскрипов (js)
    'use_minimized_css' => true,
    'use_minimized_js'  => true,

    'FLASH_TIMEOUT' => 5000,

    /* если валюта еще не задана в куке, будет произведена попытка подбора оптимально валюты по языковому параметру for_lang (SController.php beforeAction) */
    'exchange' => [
        'default' => 'usd',
        'usd' => [
            'usd' => ['name' => 'USD', 'name_lover' => 'usd', 'code' => '$', 'val' => 1.00,  'for_lang' => ['en']],
            'eur' => ['name' => 'EUR', 'name_lover' => 'eur', 'code' => '€', 'val' => 0.89,  'for_lang' => ['es', 'hu']],
            'rur' => ['name' => 'RUR', 'name_lover' => 'rur', 'code' => '₽', 'val' => 74.66, 'for_lang' => ['ru', 'kz']],
            'uah' => ['name' => 'UAH', 'name_lover' => 'uah', 'code' => '₴', 'val' => 26.96, 'for_lang' => ['ua']],
        ],
    ],
    'default_teacher_percent' => 0.75,

    'contact_phone' => "+7(495) 003-1379",

    'default_teacher_youtube_video' => "https://www.youtube.com/watch?v=fZQQN3_zol4&ab_channel=4pm",
];
