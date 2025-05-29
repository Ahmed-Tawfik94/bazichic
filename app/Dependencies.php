<?php

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use App\Controllers\BaseController;
use Illuminate\Database\Capsule\Manager as Capsule;
use App\Helpers\PluralizeExtension;
global $container;
if (!empty($app)) {
    $container = $app->getContainer();
}

$capsule = new Capsule;
$capsule->addConnection(require __DIR__ . '/config/database.php');
$capsule->setAsGlobal();
$capsule->bootEloquent();
// Database connection (using PDO)
$container['db'] = function ($container) use ($capsule) {
    return $capsule;
};
// $container['db'] = function ($container) {
//     $dsn = "mysql:host={$_ENV['HOST']};dbname={$_ENV['DB']};charset={$_ENV['CHARSET']}";
//     $pdo = new PDO($dsn, $_ENV['USER'],  $_ENV['PASS']);
//     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//     $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
//     return $pdo;
// };
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig('../app/Views');
    $view->addExtension(new \Slim\Views\TwigExtension(
        $container['router'],
        $container['request']->getUri()
    ));
    $view->addExtension(new PluralizeExtension());
    // $view->addGlobal('session', $_SESSION);
    return $view;
};
$container['upload_directory'] = __DIR__ . '/../app/uploads/documents';
$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $c['view']->render($response->withStatus(404), '404.twig', [
            "myMagic" => "Let's roll"
        ]);
    };
};
$container['flash'] = function () {
    return new \Slim\Flash\Messages();
};
$container['logger'] = function ($container) {
    $logger = new Logger('app_logger');
    $logFile = __DIR__ . '/../logs/app.log'; // Ensure the logs directory exists
    $logger->pushHandler(new StreamHandler($logFile, Logger::DEBUG));
    return $logger;
};

// Register Controllers 
// // Define a directory for your controllers
// $controllerDir = __DIR__ . '/Controllers/';

// // Automatically load all controllers
// foreach (glob($controllerDir . '*.php') as $controllerFile) {
//     if(strpos(basename($controllerFile, '.php'), 'Controller') !== false){
//         $UsecontrollerName = 'App\\Controllers\\' . basename($controllerFile, '.php');
//         $controllerName = basename($controllerFile, '.php');
//         use ($UsecontrollerName)
        
//         // Register the controller in the container
//         $container[$controllerName] = function ($c)  {
//             return new $controllerName($c);
//         };
//     }
// }
//$container['BaseController'] = function($c) {// retrieve the 'view' from the container
//    return new BaseController($c);
//};
