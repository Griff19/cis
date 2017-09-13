<?php
return [
    'name' => 'DEV! КИС Алтайская Буренка',
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'pgsql:host=192.168.0.7;dbname=cis',
            'username' => 'cis',
            'password' => 'X1O57dgrbD_f',
            'charset' => 'utf8',
            'enableSchemaCache' => true,
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'mail.nic.ru',
                'username' => 'portal@altburenka.ru',
                'password' => 'M3vJhNa39J9Vk',
                'port' => '465',
                'encryption' => 'ssl'
            ]
        ],
    ],
];
