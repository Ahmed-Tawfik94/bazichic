<?php // bootstrap/settings.php
use Monolog\Logger;

// Assumes Dotenv has been loaded, e.g., in public/index.php

return [
    'displayErrorDetails' => ($_ENV['APP_DEBUG'] ?? 'false') === 'true',
    'logErrors' => true,
    'logErrorDetails' => true,
    'logger' => [
        'name' => $_ENV['APP_NAME'] ?? 'slim-app',
        'path' => __DIR__ . '/../logs/app.log',
        'level' => Logger::DEBUG,
    ],
    'app' => [
        'name' => $_ENV['APP_NAME'] ?? 'My App Name'
    ],
    'views' => [
        'cache_path' => ($_ENV['VIEW_CACHE_DISABLED'] ?? 'false') === 'true' ? false : __DIR__ . '/../storage/views',
        'path' => __DIR__.'/../resources/Views',
    ],
    'db'=>[
        'driver'=>$_ENV['DB_DRIVER'] ?? 'mysql',
        'host'=> $_ENV['DB_HOST'] ?? '127.0.0.1',
        'port'=>$_ENV['DB_PORT'] ?? '3306',
        'database'=> $_ENV['DB_NAME'] ?? 'mydb',
        'username'=> $_ENV['DB_USERNAME'] ?? 'user',
        'password'=> $_ENV['DB_PASS'] ?? 'password',
    ],
    'upload_directory' => __DIR__ . '/../app/uploads/documents'
];
