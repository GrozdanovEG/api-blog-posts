<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Controller;

use BlogPostsHandling\Api\Repository\CategoryRepositoryByPdo;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use function MongoDB\BSON\toJSON;

class ListCategoriesController
{

    public function __invoke(Request $request, Response $response, $args): Response
    {
        $categoryRepo = new CategoryRepositoryByPdo();

        if ( $categories = $categoryRepo->fetchAll() ) {

            $categoriesMap = [];
            foreach ($categories as $c) $categoriesMap[] = $c->toMap();

            return new JsonResponse([
                "message" => "categories successfully retrieved",
                "msgid" => "categories_retrieved",
                "categories" => $categoriesMap
            ], 200);

        } else
            return new JsonResponse([
            "message" => 'categories not available',
            "msgid" => 'categories_unavailable'
        ], 404);
    }
}