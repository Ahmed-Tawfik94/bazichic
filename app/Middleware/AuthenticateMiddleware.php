<?php
namespace App\Middleware;

use App\Models\Role;
use App\Models\User;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Interfaces\RouteParserInterface;
use Slim\Routing\RouteContext;

class AuthenticateMiddleware implements MiddlewareInterface
{
    protected array $allowedRoles;
    protected RouteParserInterface $routeParser;
    protected ResponseFactoryInterface $responseFactory;

    public function __construct(
        RouteParserInterface $routeParser,
        ResponseFactoryInterface $responseFactory,
        array $allowedRoles = []
    ) {
        $this->routeParser = $routeParser;
        $this->responseFactory = $responseFactory;
        $this->allowedRoles = $allowedRoles;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();
        $routeName = $route ? $route->getName() : null;

        // Direct session access is generally discouraged in PSR-15 middleware.
        // Ideally, session data would be accessed via request attributes set by a session middleware.
        // For this refactoring, we'll keep direct $_SESSION access as it was in Slim 3.
        $session = $_SESSION ?? []; // Ensure $_SESSION exists
        $apiKey = $session['api_key'] ?? null;
        $roleId = $session['role_id'] ?? null;
        $userId = $session['userID'] ?? null;

        if ($this->isLoggedIn($apiKey)) {
            if ($this->isPublicRoute($routeName)) {
                return $this->redirectToDashboard($userId);
            }

            if (!empty($this->allowedRoles) && !$this->isRoleAllowed($roleId)) {
                return $this->responseFactory->createResponse(302)
                    ->withHeader('Location', $this->routeParser->urlFor('unauthorized'));
            }

            return $handler->handle($request);
        }

        if (!$this->isPublicRoute($routeName)) {
            $this->storeLastVisited($request);
            return $this->redirectToPath('/login'); // Using hardcoded path for now
        }

        return $handler->handle($request);
    }

    protected function isLoggedIn(?string $apiKey): bool
    {
        return !empty($apiKey) && User::isValidApiKey($apiKey);
    }

    protected function isPublicRoute(?string $routeName): bool
    {
        // Ensure route names used here are consistent with your Slim 4 route definitions
        return in_array($routeName, ['login', 'register', 'home', 'verify', 'resend-verification', 'password-recovery', 'contact-submit', 'coming-soon', 'contact', 'login_controller']);
    }

    protected function isRoleAllowed(?int $roleId): bool
    {
        if (!$roleId || empty($this->allowedRoles)) {
            return false; // If no roles are allowed, or user has no role, deny.
        }

        $userRole = Role::find($roleId); // Fetch Role model
        return $userRole && in_array(strtolower($userRole->name), array_map('strtolower', $this->allowedRoles));
    }

    protected function redirectToDashboard(?int $userId): ResponseInterface
    {
        // Default to dashboard, adjust if admin
        $redirectRouteName = 'dashboard'; // Assuming 'dashboard' is a named route
        if ($userId && User::isAdmin($userId)) {
            $redirectRouteName = 'admin-panel'; // Assuming 'admin-panel' is a named route
        }
        try {
            $url = $this->routeParser->urlFor($redirectRouteName);
        } catch (\Exception $e) {
            // Fallback if route name doesn't exist, or handle error
            $url = '/'; // Default to home or a safe fallback
            // Log error: $e->getMessage()
        }
        return $this->responseFactory->createResponse(302)->withHeader('Location', $url);
    }

    protected function storeLastVisited(ServerRequestInterface $request): void
    {
        if (session_status() == PHP_SESSION_NONE) {
            // Session should ideally be started by SessionMiddleware earlier in the stack
            // session_start(); // Avoid starting session here if possible
        }
        // Ensure $_SESSION is available
        if (isset($_SESSION)) {
            $_SESSION['last_visited'] = (string) $request->getUri();
        }
    }

    protected function redirectToPath(string $path): ResponseInterface
    {
        // For simple path redirects (not named routes)
        return $this->responseFactory->createResponse(302)->withHeader('Location', $path);
    }

    // Optional: Method to dynamically set allowed roles if middleware is registered as a singleton service
    public function setAllowedRoles(array $roles): self
    {
        $this->allowedRoles = $roles;
        return $this;
    }
}
