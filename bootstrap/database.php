<?php
use Illuminate\Database\Capsule\Manager as Capsule;

$config = $container['settings']['db'];

$capsule = new Capsule;

$capsule->addConnection(array_merge($config,[
    'charset'=>'utf8',
    'collation'=>'utf8_unicode_ci'
]));
$capsule->bootEloquent();
$capsule->setAsGlobal();

// Add to the container
$container['db'] = function () use ($capsule) {
    return $capsule;
};

//// Check if database exists
//$databaseName = $_ENV['DB_NAME'];
//$dbExists = Capsule::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?", [$databaseName]);
//
//// Create database if it does not exist
//if (!$dbExists) {
//    Capsule::statement("CREATE DATABASE `$databaseName` CHARACTER SET utf8 COLLATE utf8_unicode_ci;");
//}
//
//echo "Database checked and created if necessary.\n";