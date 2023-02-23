<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Controller;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use BlogPostsHandling\Api\Repository\CategoryRepositoryByPdo;

class DeleteCategoryController
{
    public function __invoke(Request $request, Response $response, $args): Response
    {
        $inputs = json_decode($request->getBody()->getContents(), true);
        $categoryId = $args['id'] ?? $inputs['id'];

        /** @todo to be moved in separate Validation layer  */
        if ($categoryId === null) return new JsonResponse([
            "message" => 'Invalid category ID provided',
            "msgid" => 'invalid_category_id'
        ], 400);

        $categoryRepo = new CategoryRepositoryByPdo();
        $category = $categoryRepo->findById($categoryId);

        if (! $category )
            return new JsonResponse([
                "message" => 'A category with id ['. $categoryId .'] was not found, nothing to be deleted',
                "msgid" => 'category_not_found'
            ], 404);

        else
        if ( $categoryRepo->deleteById( $category->id() ) )
            return new JsonResponse([
                "message" => 'category ['.$category->name().'] was successfully deleted',
                "msgid" => 'category_deleted',
                "catid" => $category->id(),
                "catname" => $category->name(),
                "catdescr" => $category->description()
            ], 200);

        else return new JsonResponse([
            "message" => 'the category ['.$category->name().'] was not deleted',
            "msgid" => 'category_deletion_failure'
        ], 400);
    }
}