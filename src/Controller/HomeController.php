<?php

declare(strict_types=1);

namespace BlogPostsHandling\Api\Controller;

use BlogPostsHandling\Api\Response\ResponseHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HomeController
{
    public function __invoke(Request $request, Response $response, $args): Response
    {
        $responseHandler = new ResponseHandler();

        $responseData = ['appname' => 'Blog Posts Handling Api', 'version' => '0.1'];
        $response->getBody()->write(json_encode($responseData));
        return $responseHandler
            ->type('/v1')
            ->title('home_route')
            ->status(200)
            ->detail('API home route')
            ->jsonSend($responseData);
    }
}
