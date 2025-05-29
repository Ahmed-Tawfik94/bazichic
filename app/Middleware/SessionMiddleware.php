<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class SessionMiddleware
{
    protected Twig $view;

    // Inject Twig (or any view service) via constructor
    public function __construct(Twig $view)
    {
        $this->view = $view;
    }
    public function __invoke(Request $request,Response $response, $next)
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
        $this->view->getEnvironment()->addGlobal('session', $_SESSION);

        // Call the next middleware/handler
        $response = $next($request, $response);// Correctly call the handle method of the RequestHandler

        return $response;

        // return $response;
    }
    // public function __invoke(Request $request, RequestHandler $handler): Response
    // {
    //     // Start session if not already started
       
    // }
}
