<?php
declare(strict_types=1);

namespace BlogPostsHandling\Api\Controller;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AddPostController
{
    public function __invoke(Request $request, Response $response, $args): Response
    {
        $inputData = json_decode($request->getBody()->getContents(), true);
        // post object creation logic here

        // storage logic to be added here

        return new JsonResponse(json_decode($inputData));
    }

}
