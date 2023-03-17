<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Controller;

use OpenApi\Generator;
use BlogPostsHandling\Api\Response\ResponseHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class OpenApiDocController
{
    public function __invoke(Request $request, Response $response, $args): Response
    {
        $responseHandler = new ResponseHandler();

        $path = [ __DIR__ . '/../../src' ];
        $openApiResponse = json_decode( (Generator::scan($path))->toJson(), true);

        return $responseHandler
            ->type('/v1/apidocs')
            ->title('apidocs')
            ->status(200)
            ->detail('Api documentation successfully loaded')
            ->jsonSend($openApiResponse);
    }
}