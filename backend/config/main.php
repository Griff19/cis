<?php

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    //'language' => 'ru_RU',
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'task' => [
            'class' => 'app\modules\tasks\task',
        ],
    ],
    'homeUrl' => '/admin',
    'components' => [
        'request' => [
            'baseUrl' => '/admin',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'authManager' => [
                'class' => 'yii\rbac\DbManager',
                'defaultRoles' => ['user']
            ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'pdf' => [
            'class' => 'kartik\mpdf\Pdf',
            'format' => 'A4',
            'orientation' => 'P',
            'destination' => 'I'
        ]
    ],
    'params' => $params,
];
