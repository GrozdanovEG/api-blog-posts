<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Controller;

use BlogPostsHandling\Api\Repository\CategoryRepositoryByPdo;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ListCategoriesController
{

    public function __invoke(Request $request, Response $response, $args): Response
    {
        $categoryRepo = new CategoryRepositoryByPdo();

        if ( $categories = $categoryRepo->fetchAll() ) {

            $jsonRepresentationCategoryList = [];
            foreach ($categories as $cat)
                $jsonRepresentationCategoryList[] = serialize($cat);

            $categoriesEncoded = json_encode($jsonRepresentationCategoryList, JSON_UNESCAPED_UNICODE, 512);

            return new JsonResponse([
                "message" => "categories successfully retrieved",
                "msgid" => "categories_retrieved",
                "categories" => $categoriesEncoded
            ], 200);
        }

        else return new JsonResponse([
            "message" => 'categories not available',
            "msgid" => 'categories_unavailable'
        ], 404);
    }
}