<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Controller;

use BlogPostsHandling\Api\Entity\Category;
use BlogPostsHandling\Api\Repository\CategoryRepositoryByPdo;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AddCategoryController
{
    public function __invoke(Request $request, Response $response, $args): Response
    {
        $inputs = json_decode($request->getBody()->getContents(), true);

         // @todo moving and improving the validation logic to a separated class/layer
        $validRequest = count($inputs) > 0;
        foreach (['name', 'description'] as $key)
            if ( !isset($inputs[$key]) || $inputs[$key] === '') $validRequest = false;

        if(!$validRequest) return new JsonResponse([
                "message" => 'a new category cannot be created, no sufficient input data provided',
                "msgid" => 'category_creation_failed',
                "detail" => 'wrong_input_data'
            ], 400);

        $category = Category::createFromArrayAssoc($inputs);

        if (  (new CategoryRepositoryByPdo())->store($category) )
            return new JsonResponse([
                "message" => 'category ['.$category->name().'] successfully added',
                "msgid" => 'category_added',
                "category" => [
                    "id" => $category->id(),
                    "name" => $category->name(),
                    "description" => $category->description()
                ]
            ], 201);

        else return new JsonResponse([
                "message" => 'the category ['.$category->name().'] was not added',
                "msgid" => 'category_storing_failed'
            ], 500);
    }
}