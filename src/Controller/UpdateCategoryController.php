<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Controller;

use BlogPostsHandling\Api\Entity\Category;
use BlogPostsHandling\Api\Repository\CategoryRepositoryByPdo;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UpdateCategoryController
{
    public function __invoke(Request $request, Response $response, $args): Response
    {
        $inputs = json_decode($request->getBody()->getContents(), true);
        $inputs['id'] = $args['id'] ?? $inputs['id'];

        // @todo moving the validation logic to a separated class/layer
        $validRequest = count($inputs) > 0;
        foreach (['id', 'name', 'description'] as $key)
            if ( !isset($inputs[$key]) || $inputs[$key] === '') $validRequest = false;

        if(!$validRequest)
            return new JsonResponse([
                "message" => 'the category ['.$inputs['id'].'] was not updated, no sufficient input data provided',
                "msgid" => 'category_update_failed',
                "detail" => 'wrong_input_data'
            ], 400);


        $category = Category::createFromArrayAssoc($inputs);
        $categoryRepo = new CategoryRepositoryByPdo();

        if ( $categoryRepo->store($category) )
            return new JsonResponse([
                "message" => 'category ['.$category->name().'] successfully updated with the following data',
                "msgid" => 'category_updated',
                "category" => [
                    "id" => $category->id(),
                    "name" => $category->name(),
                    "description" => $category->description()
                ]
            ], 200);

        else return new JsonResponse([
            "message" => 'the category ['.$category->name().'] was not updated',
            "msgid" => 'category_storing_failed'
        ], 400);
    }
}