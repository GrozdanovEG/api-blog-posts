<?php

declare(strict_types=1);

namespace BlogPostsHandling\Api\Controller;

use BlogPostsHandling\Api\Response\ResponseHandler;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HomeController
{
    /**
     * @OA\Get(
     *     path="/v1/",
     *     @OA\Response(response="200", description="Blog Posts Handling application home route")
     * )
     */
    public function __invoke(Request $request, Response $response, $args): Response
    {
        $responseData = [
            'appname' => 'Blog Posts Handling Api',
            'version' => '0.1',
            'description' =>
                'A blog posts management API. Posts grouped by categories with ability each post to use own thumbnail.'
        ];
        return new JsonResponse($responseData);
    }
}
