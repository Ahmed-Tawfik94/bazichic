<?php
/***********************/
namespace App\Middleware;

use App\Models\User;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Interfaces\RouteParserInterface;

class SubscriptionMiddleware implements MiddlewareInterface
{
    protected RouteParserInterface $routeParser;
    protected ResponseFactoryInterface $responseFactory;

    public function __construct(
        RouteParserInterface $routeParser,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->routeParser = $routeParser;
        $this->responseFactory = $responseFactory;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Session-based authentication check
        // Direct session access is generally discouraged in PSR-15 middleware.
        // For this refactoring, we'll keep direct $_SESSION access as it was in Slim 3.
        $session = $_SESSION ?? []; // Ensure $_SESSION exists
        $api_key = $session['api_key'] ?? null;
        
        // Check if user is logged in and is a regular user (role_id 3)
        // Note: The original middleware didn't explicitly check if $api_key was null before querying.
        // Adding a check for $api_key to prevent unnecessary queries if user is not logged in.
        if ($api_key) {
            $user = User::where('api_key', $api_key)->where('role_id', 3)->first();
            if ($user && !$user->subscription()->exists()) {
                // Assuming 'subscription-plans' is a named route
                return $this->redirectToRoute('subscription-plans');
            }
        } else {
            // If not logged in, and this middleware is protecting a route,
            // it might imply an issue with AuthenticateMiddleware not running first or allowing access.
            // For now, let it pass if no API key, assuming Auth middleware handles unauthenticated access.
            // Or, redirect to login if this middleware implies user must be logged in.
            // Let's assume for now that if api_key is null, AuthMiddleware should have handled it.
        }
        
        return $handler->handle($request);
    }

    protected function redirectToRoute(string $routeName, array $data = [], array $queryParams = []): ResponseInterface
    {
        try {
            $url = $this->routeParser->urlFor($routeName, $data, $queryParams);
        } catch (\Exception $e) {
            // Fallback if route name doesn't exist, or handle error
            // Log error: $e->getMessage()
            // For now, redirect to a generic path or handle as appropriate
            // This might indicate a configuration error (e.g., route name changed).
            // Redirecting to home as a safe fallback.
            $url = $this->routeParser->urlFor('home'); 
        }
        return $this->responseFactory->createResponse(302)->withHeader('Location', $url);
    }
}
