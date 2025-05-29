<?php
/***********************/
namespace App\Middleware;
use App\Models\User;
class SubscriptionMiddleware
{
    /**
     * Example middleware invokable class
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */

    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }
    public function __invoke($request, $response, $next)
    {
        // Session-based authentication check
        $api_key = $_SESSION['api_key'] ?? null;
        // Check if user is logged in

            $user =User::where('api_key', $api_key)->where('role_id', 3)->first();
            if (!$user->subscription()->exists()) {
                return $this->redirectTo($request, $response, 'subscription-plans');
            }
        return $next($request, $response);
    }
    protected function redirectTo($request, $response, $route)
    {
        $uri = $request->getUri()->withPath($this->container->get('router')->pathFor($route));
        return $response->withRedirect((string) $uri);
    }
}
// $authenticate = function($request, $response, $next) {

// };
