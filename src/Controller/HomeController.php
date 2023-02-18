<?php
declare(strict_types=1);

namespace BlogPostsHandling\Api\Controller;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HomeController
{
    public function __invoke(Request $request, Response $response, $args): Response
    {
        $responseData = ['appname' => 'BlogPostsHandlingApi', 'version' => '0.1'];
        $response->getBody()->write(json_encode($responseData));
        return new JsonResponse($responseData);
    }

}