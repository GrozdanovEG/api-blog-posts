<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Controller;

use BlogPostsHandling\Api\Entity\Category;
use BlogPostsHandling\Api\Repository\CategoryRepositoryByPdo;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetCategoryController
{

    public function __invoke(Request $request, Response $response, $args): Response
    {
        $inputs = json_decode($request->getBody()->getContents(), true);
        $categoryId = $inputs['id'];

        $categoryRepo = new CategoryRepositoryByPdo();

        if ( $category = $categoryRepo->findById($categoryId) )
            return new JsonResponse([
                "message" => 'category ['.$category->name().'] was successfully found',
                "msgid" => 'category_found',
                "catid" => $category->id(),
                "catname" => $category->name(),
                "catdescr" => $category->description()
            ], 200);

        else return new JsonResponse([
            "message" => 'the category ['.$category->name().'] was not found',
            "msgid" => 'category_storing_failed'
        ], 404);
    }
}

