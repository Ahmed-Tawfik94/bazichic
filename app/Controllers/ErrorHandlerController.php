<?php

namespace App\Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
class ErrorHandlerController extends BaseController
{
    function notFound (Request $request, Response $response)  {
        return $this->view->render($response->withStatus(404), '404.twig', [
            "myMagic" => "Let's roll"
        ]);
    }
    function Unauthorized (Request $request, Response $response)  {
        return $this->view->render($response->withStatus(403), 'unauthorized.twig', [
            "myMagic" => "Let's roll"
        ]);
    }

}