<?php
namespace App\Middleware;

use Detection\MobileDetect;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;


class DeviceDetectionMiddleware
{
    protected $view;
    public function __construct(Twig $view)
    {
        $this->view = $view;
    }
    public function __invoke(Request $request, Response $response, callable $next)
    {
        $detect = new MobileDetect();
        $isMobile = $detect->isMobile();

        // Determine device type
        $deviceType = $isMobile ? 'mobile'  : 'desktop';
        $this->view->getEnvironment()->addGlobal('deviceType',   $deviceType);

        // Pass device type as a request attribute
        $request = $request->withAttribute('deviceType', $deviceType);

        return $next($request, $response);
    }
}
