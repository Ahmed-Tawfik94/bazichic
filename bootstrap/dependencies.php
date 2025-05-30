<?php // bootstrap/dependencies.php
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Slim\Views\Twig;
use App\Helpers\PluralizeExtension;
use App\Helpers\countiesExtention;
use App\Middleware\DeviceDetectionMiddleware;
use App\Middleware\MaintenanceMiddleware;
use App\Middleware\SessionMiddleware;
use App\Models\SiteSetting; // For MaintenanceMiddleware
use Odan\Csrf\CsrfMiddleware;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\App; // For RouteParserInterface
use Slim\Interfaces\RouteParserInterface; // For RouteParserInterface
use Slim\Psr7\Factory\ResponseFactory; // For ResponseFactoryInterface

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        'settings' => function () {
            return require __DIR__ . '/settings.php';
        },

        Twig::class => function (ContainerInterface $container) {
            $settings = $container->get('settings')['views'];
            $twig = Twig::create($settings['path'], [
                'cache' => $settings['cache_path']
            ]);
            // Slim\\Views\\TwigExtension is for Slim 3. This needs adjustment for Slim 4.
            // For Slim 4, the Slim\Views\TwigMiddleware handles adding necessary extensions/globals.
            // Custom extensions are still added directly to Twig.
            $twig->addExtension(new PluralizeExtension());
            $twig->addExtension(new countiesExtention());
            // Example: Add base_url if it's needed globally and not handled by TwigMiddleware context
            // $twig->getEnvironment()->addGlobal('base_url', $container->get('settings')['app']['base_url'] ?? '');
            return $twig;
        },

        LoggerInterface::class => function (ContainerInterface $container) {
            $settings = $container->get('settings')['logger'];
            $logger = new Logger($settings['name']);
            $processor = new UidProcessor();
            $logger->pushProcessor($processor);
            $handler = new StreamHandler($settings['path'], $settings['level']);
            $logger->pushHandler($handler);
            return $logger;
        },

        // Placeholder for Illuminate Database (Eloquent)
        // Illuminate\\Database\\Capsule\\Manager::class => function(ContainerInterface $container) {
        //     $dbSettings = $container->get('settings')['db'];
        //     $capsule = new Illuminate\\Database\\Capsule\\Manager;
        //     $capsule->addConnection([/* ... connection details ... */]);
        //     $capsule->setAsGlobal();
        //     $capsule->bootEloquent();
        //     return $capsule;
        // }

        // PSR-7 Response Factory
        ResponseFactoryInterface::class => function (ContainerInterface $container) {
            // Slim's AppFactory can determine this, or we can be explicit
            return $container->get(ResponseFactory::class); // Or AppFactory::determineResponseFactory();
        },

        // Middleware Definitions
        SessionMiddleware::class => function (ContainerInterface $container) {
            return new SessionMiddleware($container->get(Twig::class));
        },

        DeviceDetectionMiddleware::class => function (ContainerInterface $container) {
            return new DeviceDetectionMiddleware($container->get(Twig::class));
        },

        MaintenanceMiddleware::class => function (ContainerInterface $container) {
            return new MaintenanceMiddleware(
                $container->get(Twig::class),
                new SiteSetting(), // Direct instantiation for now
                $container->get(ResponseFactoryInterface::class)
                // $container->get(RouteParserInterface::class) // If needed for more complex route checks
            );
        },

        CsrfMiddleware::class => function (ContainerInterface $container) {
            // Basic setup for Odan\Csrf\CsrfMiddleware.
            // This assumes session is started globally via SessionMiddleware or similar.
            // Odan\Csrf\CsrfMiddleware typically needs a session abstraction (like odan/psr7-session)
            // and a response factory.
            // For now, if it defaults to using native PHP sessions when no session interface is passed,
            // this might work for a basic setup. Otherwise, this will need refinement.
            // The failure handler is usually set when adding the middleware to the app or via settings.
            return new CsrfMiddleware(
                $container->get(ResponseFactoryInterface::class)
                // Note: Session handling for odan/csrf is more complex than this.
                // It expects a PSR-15 session middleware or a SessionInterface.
                // This DI might be insufficient for odan/csrf to work out-of-the-box with native $_SESSION.
            );
        },

        // Slim's Route Parser (useful for generating URLs in controllers/services)
        RouteParserInterface::class => function (ContainerInterface $container) {
            return $container->get(App::class)->getRouteCollector()->getRouteParser();
        },

        // Authentication and Subscription Middleware
        \App\Middleware\AuthenticateMiddleware::class => function (ContainerInterface $container) {
            // This provides a basic way to get an instance.
            // Allowed roles will typically be set when the middleware is applied to a route/group.
            return new \App\Middleware\AuthenticateMiddleware(
                $container->get(RouteParserInterface::class),
                $container->get(ResponseFactoryInterface::class)
                // Default empty allowedRoles, to be set via setAllowedRoles() or direct instantiation
            );
        },

        \App\Middleware\SubscriptionMiddleware::class => function (ContainerInterface $container) {
            return new \App\Middleware\SubscriptionMiddleware(
                $container->get(RouteParserInterface::class),
                $container->get(ResponseFactoryInterface::class)
            );
        },

        // Custom HTML Error Renderer
        \App\Renderers\HtmlErrorRenderer::class => function (ContainerInterface $container) {
            return new \App\Renderers\HtmlErrorRenderer(
                $container->get(Twig::class),
                $container->get(LoggerInterface::class)
            );
        },

        // Illuminate Validation
        \Illuminate\Contracts\Translation\Translator::class => function (ContainerInterface $container) {
            // Using ArrayLoader for basic setup without needing language files on disk.
            // For actual translations, FileLoader and language files in resources/lang would be needed.
            $loader = new \Illuminate\Translation\ArrayLoader();
            $locale = 'en'; // Default locale
            $translator = new \Illuminate\Translation\Translator($loader, $locale);
            return $translator;
        },

        \Illuminate\Validation\Factory::class => function (ContainerInterface $container) {
            $validatorFactory = new \Illuminate\Validation\Factory(
                $container->get(\Illuminate\Contracts\Translation\Translator::class),
                $container // Pass the container itself for resolving custom validators, presence verifiers
            );
            
            // Setup database presence verifier (optional, but common for 'unique' rule)
            // This relies on 'Illuminate\Database\Capsule\Manager' being defined in the container.
            // if ($container->has(\Illuminate\Database\Capsule\Manager::class)) {
            //     $dbCapsule = $container->get(\Illuminate\Database\Capsule\Manager::class);
            //     // Ensure the connection is resolved and available if using default connection
            //     // $connection = $dbCapsule->getConnection(); 
            //     $presenceVerifier = new \Illuminate\Validation\DatabasePresenceVerifier($dbCapsule->getDatabaseManager());
            //     $validatorFactory->setPresenceVerifier($presenceVerifier);
            // }
            return $validatorFactory;
        },

        // Optional: Define DatabasePresenceVerifierInterface if needed separately
        // \Illuminate\Validation\DatabasePresenceVerifierInterface::class => function (ContainerInterface $container) {
        //     if (!$container->has(\Illuminate\Database\Capsule\Manager::class)) {
        //         // Handle missing DB connection for validator - could throw or return a dummy/null verifier
        //         // For now, returning null or throwing an exception might be appropriate.
        //         // throw new \Exception('Illuminate\Database\Capsule\Manager not found in container, cannot set up DatabasePresenceVerifier.');
        //         return null; 
        //     }
        //     $dbCapsule = $container->get(\Illuminate\Database\Capsule\Manager::class);
        //     return new \Illuminate\Validation\DatabasePresenceVerifier($dbCapsule->getDatabaseManager());
        // }

        // Controller Definitions
        // BaseController itself is abstract, so no DI definition for it directly.
        // Child controllers will have their dependencies injected, including those needed by BaseController.

        \App\Controllers\HomeController::class => function (ContainerInterface $container) {
            return new \App\Controllers\HomeController(
                $container,
                $container->get(Twig::class),
                $container->get(\Illuminate\Database\Capsule\Manager::class),
                $container->get(RouteParserInterface::class),
                $container->get(LoggerInterface::class),
                $container->get(\SimpleFlash\Flash::class)
            );
        },

        \App\Controllers\LoginController::class => function (ContainerInterface $container) {
            return new \App\Controllers\LoginController(
                $container,
                $container->get(Twig::class),
                $container->get(\Illuminate\Database\Capsule\Manager::class),
                $container->get(RouteParserInterface::class),
                $container->get(LoggerInterface::class),
                $container->get(\App\Service\stripe\SubscriptionService::class), // Specific to LoginController
                $container->get(\SimpleFlash\Flash::class)
            );
        },

        \App\Controllers\RegisterController::class => function (ContainerInterface $container) {
            return new \App\Controllers\RegisterController(
                $container,
                $container->get(Twig::class),
                $container->get(\Illuminate\Database\Capsule\Manager::class),
                $container->get(RouteParserInterface::class),
                $container->get(LoggerInterface::class),
                $container->get(\Illuminate\Validation\Factory::class), // Specific to RegisterController
                $container->get(\SimpleFlash\Flash::class)
            );
        },

        \App\Controllers\EbookController::class => function (ContainerInterface $container) {
            return new \App\Controllers\EbookController(
                $container,
                $container->get(Twig::class),
                $container->get(\Illuminate\Database\Capsule\Manager::class),
                $container->get(RouteParserInterface::class),
                $container->get(LoggerInterface::class),
                $container->get(\SimpleFlash\Flash::class)
            );
        },

        \App\Controllers\AdminController::class => function (ContainerInterface $container) {
            return new \App\Controllers\AdminController(
                $container,
                $container->get(Twig::class),
                $container->get(\Illuminate\Database\Capsule\Manager::class),
                $container->get(RouteParserInterface::class),
                $container->get(LoggerInterface::class),
                $container->get(\App\Service\stripe\SubscriptionService::class), // Specific to AdminController
                $container->get(\SimpleFlash\Flash::class)
            );
        },
        
        // Definition for SubscriptionService if it's not auto-wireable or needs specific config
        \App\Service\stripe\SubscriptionService::class => function (ContainerInterface $container) {
            // Assuming SubscriptionService constructor takes DB and Logger, or other DI managed services
            return new \App\Service\stripe\SubscriptionService(
                 $container->get(\Illuminate\Database\Capsule\Manager::class), // Example dependency
                 $container->get(LoggerInterface::class)                   // Example dependency
            );
        },

        \SimpleFlash\Flash::class => function (ContainerInterface $container) {
            // simple-flash will use $_SESSION by default if session is already started.
            // Our SessionMiddleware should handle starting the session.
            return \SimpleFlash\Flash::getInstance();
        },
    ]);
};
