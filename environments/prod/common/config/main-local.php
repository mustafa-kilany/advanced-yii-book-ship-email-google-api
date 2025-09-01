<?php

return [
    'components' => [
        'db' => [
            'class' => \yii\db\Connection::class,
            'dsn' => 'mysql:host=localhost;dbname=advanced_book',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@common/mail',
            'useFileTransport' => false,
            
            // Mailtrap SMTP Configuration (Perfect for testing!)
            'transport' => [
                'scheme' => 'smtp',
                'host' => 'live.smtp.mailtrap.io',
                'username' => 'apismtp@mailtrap.io',
                'password' => 'YOUR_MAILTRAP_PASSWORD',
                'port' => 587,
                'encryption' => 'tls',
            ],
        ],
    ],
];
