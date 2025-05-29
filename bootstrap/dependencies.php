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
    ]);
};
