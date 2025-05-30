<?php // public/index.php
use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Slim\Views\TwigMiddleware;
use Psr\Log\LoggerInterface; // For error handler logging example

require_once __DIR__ . '/../vendor/autoload.php';

// Load .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

// Instantiate PHP-DI ContainerBuilder
$containerBuilder = new ContainerBuilder();

// Add settings definitions (settings.php returns the array directly)
$settings = require __DIR__ . '/../bootstrap/settings.php';
$containerBuilder->addDefinitions(['settings' => $settings]);

// Add other dependency definitions
$dependenciesCallable = require __DIR__ . '/../bootstrap/dependencies.php';
$dependenciesCallable($containerBuilder);

// Build PHP-DI Container instance
$container = $containerBuilder->build();

// Instantiate the app
AppFactory::setContainer($container);
$app = AppFactory::create();

// Add Routing Middleware
$app->addRoutingMiddleware();

// Add Twig middleware
$app->add(TwigMiddleware::createFromContainer($app, Twig::class));

// Add Body Parsing Middleware
$app->addBodyParsingMiddleware();

// Error Handling Middleware
$appSettings = $container->get('settings');
$displayErrorDetails = $appSettings['displayErrorDetails'];
$logErrors = $appSettings['logErrors'] ?? true;
$logErrorDetails = $appSettings['logErrorDetails'] ?? true;

$errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, $logErrors, $logErrorDetails);
// Example: Set a logger for the default error handler
// if ($container->has(LoggerInterface::class)) {
//    $logger = $container->get(LoggerInterface::class);
//    $errorHandler = $errorMiddleware->getDefaultErrorHandler();
//    $errorHandler->setLogger($logger);
// }

// Register custom HTML error renderer
$errorHandler = $errorMiddleware->getDefaultErrorHandler();
if ($container->has(\App\Renderers\HtmlErrorRenderer::class)) {
    $errorHandler->registerErrorRenderer('text/html', $container->get(\App\Renderers\HtmlErrorRenderer::class));
}


// --- Global Middleware ---
// Add middleware. Middleware is executed in Last-In-First-Out (LIFO) order.

// Session Middleware (starts session, adds session data to Twig)
// Our custom one. It also starts native session.
$app->add(\App\Middleware\SessionMiddleware::class);

// New CSRF Header Check Middleware (TheCodingMachine\CsrfHeaderMiddleware)
// This depends on a session being active.
$app->add(\TheCodingMachine\CsrfHeaderMiddleware\CsrfHeaderCheckMiddleware::class);

// Maintenance Mode Middleware
// Checks for maintenance mode. May depend on session for admin bypass.
$app->add(\App\Middleware\MaintenanceMiddleware::class);

// Device Detection Middleware
// Detects device type and makes it available to Twig and request attributes.
$app->add(\App\Middleware\DeviceDetectionMiddleware::class);

// Odan PSR-7 Session Start Middleware
// This should be one of the earliest to ensure session is started and managed for PSR-7.
// It will use the Odan\Session\PhpSession options defined in dependencies.php.
$app->add(\Odan\Session\Middleware\SessionMiddleware::class); // Corrected for odan/session v5


// --- Routes ---
// Load web routes
$webRoutes = require __DIR__ . '/../Routes/web.php';
$webRoutes($app);

// Load admin routes under the /admin prefix
$adminRoutes = require __DIR__ . '/../Routes/admin_router.php';
$app->group('/admin', $adminRoutes)
    ->add($container->get(\App\Middleware\AuthenticateMiddleware::class)->setAllowedRoles(['admin']));


// --- Database Initialization ---
// If bootstrap/database.php initializes a global Eloquent instance, require it here.
// If refactored into a service, ensure it's called/initialized via container if needed.
require __DIR__ . '/../bootstrap/database.php'; 

// Run the app
$app->run();
