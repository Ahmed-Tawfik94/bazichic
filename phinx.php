<?php
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

return
[
    'paths' => [
        'migrations' => 'z_db/migrations',
        'seeds' => 'z_db/seeds'
    ],
    'migration_base_class'=>'Database\Migrations\Migration',
    'seed_base_class'=>'Database\Seeds\Seed',
    'templates'=>[
        'file'=>'Database/MigrationTemplate.php.dist',
        'seedFile'=>'Database/SeedTemplate.php.dist'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'development',
        'production' => [
            'adapter' => 'mysql',
            'host' => $_ENV['DB_HOST'],
            'name' => $_ENV['DB_NAME'],
            'user' => $_ENV['DB_USERNAME'],
            'pass' => $_ENV['DB_PASS'],
            'port' => $_ENV['DB_PORT'],
            'charset' => $_ENV['DB_CHARSET'],
        ],
        'development' => [
            'adapter' => 'mysql',
            'host' => $_ENV['DB_HOST'],
            'name' => $_ENV['DB_NAME'],
            'user' => $_ENV['DB_USERNAME'],
            'pass' => $_ENV['DB_PASS'],
            'port' => $_ENV['DB_PORT'],
            'charset' => $_ENV['DB_CHARSET'],
        ]
    ],
    'version_order' => 'creation'
];
