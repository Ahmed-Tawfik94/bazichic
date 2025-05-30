<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Views\Twig;
use Odan\Session\SessionInterface; // Import SessionInterface
use Odan\Session\Middleware\SessionStartMiddleware; // To get SESSION_ATTRIBUTE constant

class SessionMiddleware implements MiddlewareInterface
{
    protected Twig $view;

    public function __construct(Twig $view)
    {
        $this->view = $view;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Session is now started by Odan\Session\Middleware\SessionStartMiddleware.
        // This middleware's role is now primarily to make session data available to Twig.

        $sessionAttributeName = SessionStartMiddleware::SESSION_ATTRIBUTE; // Default is 'session'
        $session = $request->getAttribute($sessionAttributeName);

        if ($session instanceof SessionInterface) {
            // Expose the session object itself to Twig.
            // Templates can then use session.get('key'), session.all(), etc.
            $this->view->getEnvironment()->addGlobal('app_session', $session);
            
            // For compatibility with old templates that might use `session.userID`, etc.
            // you could also add all session data. However, it's better to update templates
            // to use `app_session.get('userID')`.
            // $this->view->getEnvironment()->addGlobal('session', $session->all());
        } else {
            // Fallback if session attribute isn't found or is not the expected type.
            // This indicates an issue with SessionStartMiddleware not running or failing.
            $this->view->getEnvironment()->addGlobal('app_session', null);
            // $this->view->getEnvironment()->addGlobal('session', []); // For old template compatibility
        }
        
        $response = $handler->handle($request);

        return $response;
    }
}
