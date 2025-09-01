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
            // Set to false to send real emails instead of storing in files
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
            
            // Alternative: Gmail SMTP Configuration
            // 'transport' => [
            //     'scheme' => 'smtps',
            //     'host' => 'smtp.gmail.com',
            //     'username' => 'your-email@gmail.com',
            //     'password' => 'your-app-password',
            //     'port' => 465,
            // ],
        ],
    ],
];
