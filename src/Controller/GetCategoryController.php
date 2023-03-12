<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Controller;

use BlogPostsHandling\Api\Repository\CategoryRepositoryByPdo;
use BlogPostsHandling\Api\Response\ResponseHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetCategoryController
{
    public function __invoke(Request $request, Response $response, $args): Response
    {
        $inputs = json_decode($request->getBody()->getContents(), true);
        $categoryId = $args['id'] ?? $inputs['id'];

        $responseHandler = new ResponseHandler();
        // @todo validation to be implemented

        $categoryRepo = new CategoryRepositoryByPdo();

        if ( $category = $categoryRepo->findById($categoryId) )
            return $responseHandler
                ->type('/v1/category_found')
                ->title('category_found')
                ->status(200)
                ->detail('category ['.$category->name().'] was successfully found')
                ->jsonSend(['category' => $category->toMap()]);

        else return $responseHandler
            ->type('/v1/errors/category_not_found')
            ->title('category_not_found')
            ->status(404)
            ->detail('A category with id ['. $categoryId .'] was not found, nothing to be retrieved')
            ->jsonSend();
    }
}

