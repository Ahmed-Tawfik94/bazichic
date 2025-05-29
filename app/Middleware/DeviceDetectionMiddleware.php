<?php
namespace App\Middleware;

use Detection\MobileDetect;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Views\Twig;

class DeviceDetectionMiddleware implements MiddlewareInterface
{
    protected Twig $view;

    public function __construct(Twig $view)
    {
        $this->view = $view;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $detect = new MobileDetect();
        $isMobile = $detect->isMobile();

        // Determine device type
        $deviceType = $isMobile ? 'mobile' : 'desktop';
        $this->view->getEnvironment()->addGlobal('deviceType', $deviceType);

        // Pass device type as a request attribute
        $request = $request->withAttribute('deviceType', $deviceType);

        $response = $handler->handle($request);
        
        return $response;
    }
}
