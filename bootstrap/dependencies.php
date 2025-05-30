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
use App\Models\SiteSetting; 
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface; // Added
use Slim\App; 
use Slim\Interfaces\RouteParserInterface; 
use Slim\Psr7\Factory\ResponseFactory; 
use Slim\Psr7\Factory\StreamFactory; // Added

// PSR-7 Session
use Odan\Session\PhpSession;
use Odan\Session\SessionInterface;

// New CSRF Middleware
use TheCodingMachine\CsrfHeaderMiddleware\CsrfHeaderCheckMiddleware;

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
            $twig->addExtension(new PluralizeExtension());
            $twig->addExtension(new countiesExtention());
            
            if ($container->has(\SimpleFlash\Flash::class)) {
                $flash = $container->get(\SimpleFlash\Flash::class);
                $twig->getEnvironment()->addGlobal('flash_messages', $flash->getMessages());
                $twig->getEnvironment()->addGlobal('flash', $flash);
            }

            // Add CSRF token to Twig for client-side access (e.g., in meta tag for AJAX)
            // This token is managed by CsrfHeaderCheckMiddleware in the session.
            if ($container->has(SessionInterface::class)) {
                $session = $container->get(SessionInterface::class);
                $csrfToken = $session->get('csrf_token'); // Default session key for the token
                $twig->getEnvironment()->addGlobal('csrf_token', $csrfToken);
            }
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

        ResponseFactoryInterface::class => function (ContainerInterface $container) {
            return $container->get(ResponseFactory::class); // Provided by Slim\Psr7
        },

        StreamFactoryInterface::class => function (ContainerInterface $container) {
            return $container->get(StreamFactory::class); // Provided by Slim\Psr7
        },
        
        SessionInterface::class => function (ContainerInterface $container) {
            $appSettings = $container->get('settings');
            $sessionSettings = $appSettings['session'] ?? [];
            $phpSessionOptions = [
                'name' => $appSettings['app']['name'] ? str_replace(' ', '_', strtolower($appSettings['app']['name'])) . '_session' : 'bazichic_session',
                'cache_expire' => $sessionSettings['cache_expire'] ?? 0,
                'lazy_write' => $sessionSettings['lazy_write'] ?? true,
                'gc_probability' => $sessionSettings['gc_probability'] ?? 1,
                'gc_divisor' => $sessionSettings['gc_divisor'] ?? 100,
                'cookie_httponly' => $sessionSettings['cookie_httponly'] ?? true,
                'cookie_secure' => $sessionSettings['cookie_secure'] ?? ($_ENV['APP_ENV'] === 'production'),
                'cookie_samesite' => $sessionSettings['cookie_samesite'] ?? 'Lax',
                'cookie_lifetime' => $sessionSettings['cookie_lifetime'] ?? 3600 * 1, 
            ];
            return new PhpSession($phpSessionOptions);
        },
        PhpSession::class => function (ContainerInterface $container) {
            return $container->get(SessionInterface::class);
        },

        CsrfHeaderCheckMiddleware::class => function (ContainerInterface $container) {
            // Configuration for CsrfHeaderCheckMiddleware v2.0.0
            $config = [
                // 'header_name' => 'X-CSRF-Token', // Default
                // 'session_key' => 'csrf_token',   // Default
                // 'token_length' => 32,            // Default
                // 'unprotected_paths' => [],       // Default
                // 'methods' => ['POST', 'PUT', 'DELETE', 'PATCH'], // Default
                // 'fail_on_missing_header' => true // Default
            ];
            return new CsrfHeaderCheckMiddleware(
                $container->get(ResponseFactoryInterface::class),
                $container->get(StreamFactoryInterface::class),
                $container->get(SessionInterface::class), // SessionInterface is now the third argument
                $config
            );
        },

        SessionMiddleware::class => function (ContainerInterface $container) { 
            return new SessionMiddleware($container->get(Twig::class));
        },
        DeviceDetectionMiddleware::class => function (ContainerInterface $container) {
            return new DeviceDetectionMiddleware($container->get(Twig::class));
        },
        MaintenanceMiddleware::class => function (ContainerInterface $container) {
            return new MaintenanceMiddleware(
                $container->get(Twig::class),
                new SiteSetting(),
                $container->get(ResponseFactoryInterface::class)
            );
        },

        RouteParserInterface::class => function (ContainerInterface $container) {
            return $container->get(App::class)->getRouteCollector()->getRouteParser();
        },

        \App\Middleware\AuthenticateMiddleware::class => function (ContainerInterface $container) {
            return new \App\Middleware\AuthenticateMiddleware(
                $container->get(RouteParserInterface::class),
                $container->get(ResponseFactoryInterface::class)
            );
        },
        \App\Middleware\SubscriptionMiddleware::class => function (ContainerInterface $container) {
            return new \App\Middleware\SubscriptionMiddleware(
                $container->get(RouteParserInterface::class),
                $container->get(ResponseFactoryInterface::class)
            );
        },

        \App\Renderers\HtmlErrorRenderer::class => function (ContainerInterface $container) {
            return new \App\Renderers\HtmlErrorRenderer(
                $container->get(Twig::class),
                $container->get(LoggerInterface::class)
            );
        },

        \Illuminate\Contracts\Translation\Translator::class => function (ContainerInterface $container) {
            $loader = new \Illuminate\Translation\ArrayLoader();
            $locale = 'en';
            $translator = new \Illuminate\Translation\Translator($loader, $locale);
            return $translator;
        },
        \Illuminate\Validation\Factory::class => function (ContainerInterface $container) {
            $validatorFactory = new \Illuminate\Validation\Factory(
                $container->get(\Illuminate\Contracts\Translation\Translator::class),
                $container
            );
            return $validatorFactory;
        },

        // Controller Definitions
        \App\Controllers\HomeController::class => function (ContainerInterface $container) {
            return new \App\Controllers\HomeController(
                $container, $container->get(Twig::class),
                $container->get(\Illuminate\Database\Capsule\Manager::class),
                $container->get(RouteParserInterface::class),
                $container->get(LoggerInterface::class),
                $container->get(\SimpleFlash\Flash::class)
            );
        },
        \App\Controllers\LoginController::class => function (ContainerInterface $container) {
            return new \App\Controllers\LoginController(
                $container, $container->get(Twig::class),
                $container->get(\Illuminate\Database\Capsule\Manager::class),
                $container->get(RouteParserInterface::class),
                $container->get(LoggerInterface::class),
                $container->get(\App\Service\stripe\SubscriptionService::class),
                $container->get(\SimpleFlash\Flash::class)
            );
        },
        \App\Controllers\RegisterController::class => function (ContainerInterface $container) {
            return new \App\Controllers\RegisterController(
                $container, $container->get(Twig::class),
                $container->get(\Illuminate\Database\Capsule\Manager::class),
                $container->get(RouteParserInterface::class),
                $container->get(LoggerInterface::class),
                $container->get(\Illuminate\Validation\Factory::class),
                $container->get(\SimpleFlash\Flash::class)
            );
        },
        \App\Controllers\EbookController::class => function (ContainerInterface $container) {
            return new \App\Controllers\EbookController(
                $container, $container->get(Twig::class),
                $container->get(\Illuminate\Database\Capsule\Manager::class),
                $container->get(RouteParserInterface::class),
                $container->get(LoggerInterface::class),
                $container->get(\SimpleFlash\Flash::class)
            );
        },
        \App\Controllers\AdminController::class => function (ContainerInterface $container) {
            return new \App\Controllers\AdminController(
                $container, $container->get(Twig::class),
                $container->get(\Illuminate\Database\Capsule\Manager::class),
                $container->get(RouteParserInterface::class),
                $container->get(LoggerInterface::class),
                $container->get(\App\Service\stripe\SubscriptionService::class),
                $container->get(\SimpleFlash\Flash::class)
            );
        },
        \App\Service\stripe\SubscriptionService::class => function (ContainerInterface $container) {
            return new \App\Service\stripe\SubscriptionService(
                 $container->get(\Illuminate\Database\Capsule\Manager::class),
                 $container->get(LoggerInterface::class)
            );
        },
        \SimpleFlash\Flash::class => function (ContainerInterface $container) {
            return \SimpleFlash\Flash::getInstance();
        },
    ]);
};
