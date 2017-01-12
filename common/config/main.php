<?php
return [
    'timeZone' => 'Asia/Krasnoyarsk',
    'language' => 'ru-RU',
    //'sourceLanguage' => 'en-US',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'name' => 'КИС Алтайская Буренка',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'formatter' => [
            'class' => 'microinginer\humanFormatter\HumanFormatter',
            'locale' => 'ru-RU',
            'dateFormat' => 'dd.MM.y',
            'timeFormat' => 'H:mm:ss',
            'datetimeFormat' => 'dd.MM.y H:mm:ss',
            'defaultTimeZone' => 'Asia/Krasnoyarsk',
            'nullDisplay' => '-'
        ],
    ],
];
