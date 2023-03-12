<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Controller;

use BlogPostsHandling\Api\Repository\CategoryRepositoryByPdo;
use BlogPostsHandling\Api\Response\ResponseHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ListCategoriesController
{
    public function __invoke(Request $request, Response $response, $args): Response
    {
        $responseHandler = new ResponseHandler();

        if ( $categories = (new CategoryRepositoryByPdo)->fetchAll() ) {
            return $responseHandler
                ->type('/v1/categories_retrieved')
                ->title('categories_retrieved')
                ->status(200)
                ->detail('categories successfully retrieved')
                ->jsonSend(["categories" => array_map(fn($c) => $c->toMap(), $categories)]);

        } else
            return $responseHandler
                ->type('/v1/categories_unavailable')
                ->title('categories_unavailable')
                ->status(404)
                ->detail('categories not available')
                ->jsonSend();
    }
}