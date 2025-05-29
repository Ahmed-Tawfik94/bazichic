<?php
namespace App\Middleware;

use App\Models\Role;
use App\Models\User;

class AuthenticateMiddleware
{
    protected $allowedRoles;

    public function __construct(array $allowedRoles = [])
    {
        $this->allowedRoles = $allowedRoles;
    }

    public function __invoke($request, $response, $next)
    {
        $route = $request->getAttribute('route');
        $routeName = $route ? $route->getName() : null;

        $session = $_SESSION;
        $apiKey = $session['api_key'] ?? null;
        $roleId = $session['role_id'] ?? null;
        $userId = $session['userID'] ?? null;

        if ($this->isLoggedIn($apiKey)) {
            if ($this->isPublicRoute($routeName)) {
                return $this->redirectToDashboard($request, $response, $userId);
            }

            if (!$this->isRoleAllowed($roleId)) {
                return $response->withRedirect('/unauthorized');
            }

            return $next($request, $response);
        }

        if (!$this->isPublicRoute($routeName)) {
            $this->storeLastVisited($request);
            return $this->redirectTo($response, '/login');
        }

        return $next($request, $response);
    }

    protected function isLoggedIn(?string $apiKey): bool
    {
        return !empty($apiKey) && User::isValidApiKey($apiKey);
    }

    protected function isPublicRoute(?string $routeName): bool
    {
        return in_array($routeName, ['login', 'register']);
    }

    protected function isRoleAllowed(?int $roleId): bool
    {
        if (!$roleId) {
            return false;
        }

        $roles = Role::pluck('name', 'id')->toArray();
        return in_array($roles[$roleId] ?? null, $this->allowedRoles);
    }

    protected function redirectToDashboard($request, $response, $userId)
    {
        $redirectRoute = User::isAdmin($userId) ? '/admin/admin-panel' : '/dashboard';
        return $this->redirectTo($response, $redirectRoute);
    }

    protected function storeLastVisited($request): void
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['last_visited'] = (string) $request->getUri();
    }

    protected function redirectTo($response, string $route)
    {
        return $response->withRedirect($route);
    }
}
