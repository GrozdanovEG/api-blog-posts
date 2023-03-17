<?php

declare(strict_types=1);

namespace BlogPostsHandling\Api\Controller;

use Laminas\Diactoros\Response\JsonResponse;
use OpenApi\Generator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class OpenApiDocController
{
    /**
     * @OA\Get(
     *     path="/v1/apidocs",
     *     @OA\Response(response="200", description="Getting the API docs in JSON format")
     * )
     */
    public function __invoke(Request $request, Response $response, $args): Response
    {
        $path = [ __DIR__ . '/../../src' ];
        $openApiResponse = json_decode((Generator::scan($path))->toJson(), true);

        return new JsonResponse($openApiResponse);
    }
}
