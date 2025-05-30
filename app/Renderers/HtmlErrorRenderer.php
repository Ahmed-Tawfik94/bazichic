<?php
namespace App\Renderers;

use Slim\Interfaces\ErrorRendererInterface;
use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Throwable; // Import Throwable
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpMethodNotAllowedException;
// Add other Slim HTTP exception types as needed

class HtmlErrorRenderer implements ErrorRendererInterface
{
    private Twig $twig;
    private LoggerInterface $logger;

    public function __construct(Twig $twig, LoggerInterface $logger)
    {
        $this->twig = $twig;
        $this->logger = $logger;
    }

    public function __invoke(Throwable $exception, bool $displayErrorDetails): string
    {
        // Log the error
        $this->logger->error($exception->getMessage(), ['exception' => $exception]);

        $statusCode = 500;
        $title = 'Error';
        $message = 'An unexpected error occurred.';
        $template = 'error.twig'; // Default error template

        if ($exception instanceof HttpNotFoundException) {
            $statusCode = 404;
            $title = 'Page Not Found';
            $message = 'Oops! The page you are looking for does not exist.';
            $template = '404.twig'; // Specific 404 template
        } elseif ($exception instanceof HttpMethodNotAllowedException) {
            $statusCode = 405;
            $title = 'Method Not Allowed';
            $message = 'The HTTP method used is not allowed for this resource.';
            // Optionally, you could suggest allowed methods: $exception->getAllowedMethods()
            $template = 'error.twig'; // Or a specific 405.twig
        }
        // Add more specific Http exception checks if needed (e.g., HttpForbiddenException for 403)

        $data = [
            'page' => [ // Match structure if existing templates expect 'page.message', 'page.title'
                'title' => $title,
                'message' => $message,
            ],
            'statusCode' => $statusCode
        ];

        if ($displayErrorDetails) {
            $data['error'] = [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
            ];
        }
        
        // Ensure the status code is passed for the response, though renderer only returns string.
        // The ErrorMiddleware handles setting the response status code.
        // Here we just ensure the template has access to it if needed.

        try {
            // Slim 4 Twig-View's render method expects ResponseInterface as first argument
            // This is a simple error renderer, it only returns a string.
            // The actual response with status code is handled by ErrorMiddleware.
            // So, we'll directly use Twig's environment to render to string.
            return $this->twig->getEnvironment()->render($template, $data);
        } catch (\Exception $e) {
            // Fallback if Twig rendering fails
            $this->logger->error('Error rendering error page: ' . $e->getMessage(), ['exception' => $e]);
            if ($displayErrorDetails) {
                return 'Error rendering page: ' . $e->getMessage() . '<br>Original exception: ' . $exception->getMessage();
            }
            return 'An unexpected error occurred. Please try again later.';
        }
    }
}
