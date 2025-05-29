<?php

namespace App\Middleware;

use App\Models\SiteSetting;
use App\Models\User; // Assuming User model is in this namespace
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Views\Twig;
// Potentially use Slim\Interfaces\RouteParserInterface if needed for more complex route checks
// use Slim\Interfaces\RouteParserInterface;

class MaintenanceMiddleware implements MiddlewareInterface
{
    protected Twig $view;
    protected SiteSetting $siteSetting;
    protected ResponseFactoryInterface $responseFactory;
    // protected RouteParserInterface $routeParser; // If needed

    public function __construct(
        Twig $view,
        SiteSetting $siteSetting,
        ResponseFactoryInterface $responseFactory
        // RouteParserInterface $routeParser // If needed
    ) {
        $this->view = $view;
        $this->siteSetting = $siteSetting;
        $this->responseFactory = $responseFactory;
        // $this->routeParser = $routeParser; // If needed
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $isMaintenanceModeOn = $this->siteSetting->isMaintenanceModeOn();
        $userID = $_SESSION['userID'] ?? null; // Direct session access, common in transition
        $isAdmin = $userID && User::isAdmin($userID); // Assumes User model has isAdmin static method

        $routePath = $request->getUri()->getPath();

        // Normalize path for comparison (e.g., ensure leading slash, remove trailing)
        $normalizedRoutePath = '/' . trim($routePath, '/');
        
        // Apply maintenance mode only for customers (non-admin users)
        // and not on the /admin path itself or /login
        $isCustomerSide = !preg_match('#^/admin#', $normalizedRoutePath);

        if ($isMaintenanceModeOn && !$isAdmin && $isCustomerSide) {
            if ($normalizedRoutePath === '/login') {
                return $handler->handle($request); // Allow access to login page
            }
            // Render maintenance page
            $response = $this->responseFactory->createResponse(503) // 503 Service Unavailable
                ->withHeader('Content-Type', 'text/html');
            return $this->view->render($response, 'maintenance.twig');
        }

        return $handler->handle($request);
    }
}
