<?php

declare(strict_types=1);

namespace BlogPostsHandling\Api\Controller;

use BlogPostsHandling\Api\Repository\CategoryRepositoryByPdo;
use BlogPostsHandling\Api\Response\ResponseHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Throwable;

class ListCategoriesController
{
    /**
     * @OA\Get(
     *     path="/v1/list/categories",
     *     @OA\Response(response="200", description="List all existing categories route")
     * )
     */
    public function __invoke(Request $request, Response $response, $args): Response
    {
        $responseHandler = new ResponseHandler();

        try {
            $categories = (new CategoryRepositoryByPdo())->fetchAll();
            return $responseHandler
                ->type('/v1/categories_retrieved')
                ->title('categories_retrieved')
                ->status(200)
                ->detail('categories successfully retrieved')
                ->jsonSend(["categories" => array_map(fn($c) => $c->toMap(), $categories)]);
        } catch (Throwable $th) {
            error_log('Error occurred -> ' .
                "File: {$th->getFile()}:{$th->getLine()}, message: {$th->getMessage()}" . PHP_EOL);

            return $responseHandler
                ->type('/v1/categories_unavailable')
                ->title('categories_unavailable')
                ->status(500)
                ->detail('Categories cannot be retrieved due to a server error')
                ->jsonSend();
        }
    }
}
