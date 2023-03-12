<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Controller;

use BlogPostsHandling\Api\Response\ResponseHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use BlogPostsHandling\Api\Repository\CategoryRepositoryByPdo;

class DeleteCategoryController
{
    public function __invoke(Request $request, Response $response, $args): Response
    {
        $inputs = json_decode($request->getBody()->getContents(), true);
        $categoryId = $args['id'] ?? $inputs['id'];

        $responseHandler = new ResponseHandler();

        /** @todo to be moved in separate Validation layer  */
        if ($categoryId === null) return $responseHandler
            ->type('/v1/errors/invalid_category_id')
            ->title('invalid_category_id')
            ->status(400)
            ->detail('Invalid category ID provided, nothing to be deleted')
            ->jsonSend();

        $categoryRepo = new CategoryRepositoryByPdo();
        $category = $categoryRepo->findById($categoryId);

        if (! $category ) return $responseHandler
                ->type('/v1/errors/category_not_found')
                ->title('category_not_found')
                ->status(404)
                ->detail('A category with id ['. $categoryId .'] was not found, nothing to be deleted')
                ->jsonSend();

        elseif ( $categoryRepo->deleteById( $category->id() ) )
            return $responseHandler
                ->type('/v1/category_deleted')
                ->title('category_deleted')
                ->status(200)
                ->detail('category ['.$category->name().'] was successfully deleted')
                ->jsonSend([$category->toMap()]);

        else return $responseHandler
            ->type('/v1/errors/category_deletion_failure')
            ->title('category_deletion_failure')
            ->status(500)
            ->detail('the category ['.$category->name().'] was not deleted due to a server error')
            ->jsonSend([$category->toMap()]);
    }
}