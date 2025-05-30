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
use App\Middleware\SessionMiddleware; // The custom one that starts native sessions
use App\Models\SiteSetting; 
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\App; 
use Slim\Interfaces\RouteParserInterface; 
use Slim\Psr7\Factory\ResponseFactory; 

// New includes for PSR-7 Session and Tachyons CSRF
use Odan\Session\PhpSession;
use Odan\Session\SessionInterface;
use TheCodingMachine\Tachyons\Csrf\CsrfMiddleware as TachyonsCsrfMiddleware;
use TheCodingMachine\Tachyons\Csrf\CsrfTokenManager;
use TheCodingMachine\Tachyons\Csrf\CsrfTokenManagerInterface;

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

            // Add CSRF token variables globally to Twig using Tachyons CsrfTokenManager
            if ($container->has(CsrfTokenManagerInterface::class)) {
                $csrfTokenManager = $container->get(CsrfTokenManagerInterface::class);
                // Tachyons typically uses one token for all forms unless scoped.
                // The default token name is often '__csrf_token'.
                // The CsrfMiddleware adds input fields to forms automatically if configured.
                // For manual addition or access in JS, you might need these.
                $token = $csrfTokenManager->getToken(); // Gets the default token
                $twig->getEnvironment()->addGlobal('csrf_token_name', $token->getInputName());
                $twig->getEnvironment()->addGlobal('csrf_token_value', $token->getValue());
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
            return $container->get(ResponseFactory::class);
        },
        
        // PSR-7 Session Management (Odan\Session)
        SessionInterface::class => function (ContainerInterface $container) {
            $appSettings = $container->get('settings'); // Get all settings
            $sessionSettings = $appSettings['session'] ?? []; // Get session specific settings
            $phpSessionOptions = [
                // Use 'bazichic_session' or a name from settings if available
                'name' => $appSettings['app']['name'] ? str_replace(' ', '_', strtolower($appSettings['app']['name'])) . '_session' : 'bazichic_session',
                'cache_expire' => $sessionSettings['cache_expire'] ?? 0,
                'lazy_write' => $sessionSettings['lazy_write'] ?? true,
                'gc_probability' => $sessionSettings['gc_probability'] ?? 1,
                'gc_divisor' => $sessionSettings['gc_divisor'] ?? 100,
                'cookie_httponly' => $sessionSettings['cookie_httponly'] ?? true,
                'cookie_secure' => $sessionSettings['cookie_secure'] ?? ($_ENV['APP_ENV'] === 'production'), // Example: true in production
                'cookie_samesite' => $sessionSettings['cookie_samesite'] ?? 'Lax',
                'cookie_lifetime' => $sessionSettings['cookie_lifetime'] ?? 3600 * 1, // 1 hour
            ];
            
            // Session save path from general settings if defined (e.g., settings.php)
            // if (!empty($appSettings['session_save_path']) && is_writable($appSettings['session_save_path'])) {
            //    $phpSessionOptions['save_path'] = $appSettings['session_save_path'];
            // } elseif (is_writable(__DIR__ . '/../storage/sessions')) { // Fallback to a default writable path
            //    $phpSessionOptions['save_path'] = __DIR__ . '/../storage/sessions';
            // }
            // Note: The Odan\Session\PhpSession constructor takes $options as its first argument.
            // It does not directly handle 'save_path' via constructor options.
            // save_path is typically set via session_save_path() before session_start()
            // or via ini_set('session.save_path', ...).
            // Odan\Session\Middleware\SessionStartMiddleware handles session_start with these options.
            
            return new PhpSession($phpSessionOptions);
        },
        PhpSession::class => function (ContainerInterface $container) {
            return $container->get(SessionInterface::class);
        },

        // New CSRF Middleware (TheCodingMachine\Tachyons\Csrf)
        CsrfTokenManagerInterface::class => function (ContainerInterface $container) {
            return new CsrfTokenManager($container->get(SessionInterface::class));
        },
        TachyonsCsrfMiddleware::class => function (ContainerInterface $container) {
            return new TachyonsCsrfMiddleware(
                $container->get(CsrfTokenManagerInterface::class),
                $container->get(ResponseFactoryInterface::class)
                // Add failure handler if needed:
                // , function (ServerRequestInterface $request) {
                //     $response = $this->responseFactory->createResponse(403); // Or your desired status
                //     $response->getBody()->write('CSRF validation failed.');
                //     return $response;
                // }
            );
        },

        // Middleware Definitions (existing custom middleware)
        SessionMiddleware::class => function (ContainerInterface $container) { // Custom session starter
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
