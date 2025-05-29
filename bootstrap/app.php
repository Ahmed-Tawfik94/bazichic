<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Models\User;
use App\Providers\ServiceProvider;
use Cake\Database\Connection;
use Monolog\Handler\StreamHandler;
use Slim\App;
use Slim\Flash\Messages;
use Monolog\Logger;
use App\Helpers\PluralizeExtension;
use \App\Models\SiteSetting;
const ROOT_FOLDER = __DIR__;
date_default_timezone_set('UTC'); // Set to your desired timezone
// Application settings
// Load .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();


$app = new App([
    'settings' => [
        'displayErrorDetails' =>$_ENV['APP_DEBUG'],
        // Only set this if you need access to route within middleware
        'determineRouteBeforeAppMiddleware' => true,
        'logger' => [
            'name' => 'slim-app',
            'level' => Logger::DEBUG,
            'path' => __DIR__ . '/../logs/app.log',
        ],
        'app' => [
            'name' => $_ENV['APP_NAME']
        ],
        'views' => [
            'cache' => $_ENV['VIEW_CACHE_DISABLED'] === 'true' ? false : __DIR__ . '/../storage/views'
        ],
        'db'=>[
            'driver'=>$_ENV['DB_DRIVER'],
            'host'=> $_ENV['DB_HOST'],
            'port'=>$_ENV['DB_PORT'],
            'database'=> $_ENV['DB_NAME'],
            'username'=> $_ENV['DB_USERNAME'],
            'password'=> $_ENV['DB_PASS'],

        ]
    ]
]);
$container = $app->getContainer();
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(__DIR__.'/../resources/Views',[
        'cache' => $container->settings['views']['cache']
    ]);
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new \Slim\Views\TwigExtension(
        $container['router'],
        $basePath
    ));
    $view->addExtension(new PluralizeExtension());
    $view->addExtension(new \App\Helpers\countiesExtention());
    // $view->addGlobal('session', $_SESSION);
    return $view;
};
$container['upload_directory'] = __DIR__ . '/../app/uploads/documents';
$container['flash'] = function () {
    return new \Slim\Flash\Messages();
};
$container['logger'] = function ($container) {
    $logger = new Logger('app_logger');
    $logFile = __DIR__ . '/../logs/app.log'; // Ensure the logs directory exists
    $logger->pushHandler(new StreamHandler($logFile, Logger::DEBUG));
    return $logger;
};
$container['flash'] = function () {
    return new Messages();
};
$container['notFoundHandler'] = function ($container) {
    return function ($request, $response) use ($container) {
        return $container['view']->render($response->withStatus(404), '404.twig', [
            'message' => 'Oops! The page you are looking for does not exist.'
        ]);
    };
};

ServiceProvider::register($container);
$app->add(function ($request, $response, $next) use ($container) {
    $settings = new SiteSetting();
    $isMaintenanceModeOn = $settings->isMaintenanceModeOn();

    $userID = $_SESSION['userID'] ?? null;
    $isAdmin = $userID && User::isAdmin($userID);

    $route = $request->getUri()->getPath();

    // Apply maintenance mode only for customers
    $isCustomerSide = !preg_match('#^/admin#', $route); // Adjust regex based on admin route structure

    if ($isMaintenanceModeOn && !$isAdmin && $isCustomerSide) {
        if ($route === '/login') {
            return $next($request, $response); // Allow customers to access login
        }
        return $container->get('view')->render($response, 'maintenance.twig');
    }

    return $next($request, $response);
});


$app->add(new \App\Middleware\SessionMiddleware($container->get('view')));
$app->add(new \App\Middleware\DeviceDetectionMiddleware($container->get('view')));


require __DIR__ . '/database.php';
require __DIR__ . '/../Routes/web.php';
require __DIR__ . '/../Routes/admin_router.php';