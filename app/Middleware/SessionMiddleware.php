<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Views\Twig;

class SessionMiddleware implements MiddlewareInterface
{
    protected Twig $view;

    // Inject Twig (or any view service) via constructor
    public function __construct(Twig $view)
    {
        $this->view = $view;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start([
                'use_only_cookies' => true, // Prevent session ID in URLs
                'cookie_httponly'  => true, // Prevent JavaScript from accessing session cookies
                'cookie_secure'    => isset($_SERVER['HTTPS']), // Secure cookies only on HTTPS
                'cookie_lifetime'  => 3600 * 1, // Shorten to 1 hour
                'cookie_samesite'  => 'Lax' // Add SameSite attribute
            ]);
//            session_regenerate_id(true);
        }

        // Inject session into Twig globally
        // Ensure $_SESSION is available and populated before trying to access it.
        if (isset($_SESSION)) {
            $this->view->getEnvironment()->addGlobal('session', $_SESSION);
        } else {
            // Optionally initialize $_SESSION or add an empty array to Twig if session didn't start
            $this->view->getEnvironment()->addGlobal('session', []);
        }
        
        $response = $handler->handle($request);

        return $response;
    }
}
