<?php

namespace BlogPostsHandling\Api\Controller;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetPostController
{
    public function __invoke(Request $request, Response $response, $args): Response
    {
        $inputData = json_decode($request->getBody()->getContents(), true);
        // storage retrieving logic to be added here
        // post object creation logic here
        var_dump($args); exit;

        return new JsonResponse($inputData);
    }
}