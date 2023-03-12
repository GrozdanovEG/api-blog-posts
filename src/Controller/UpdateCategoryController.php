<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Controller;

use BlogPostsHandling\Api\Response\ResponseHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use BlogPostsHandling\Api\Entity\Category;
use BlogPostsHandling\Api\Repository\CategoryRepositoryByPdo;

class UpdateCategoryController
{
    public function __invoke(Request $request, Response $response, $args): Response
    {
        $inputs = json_decode($request->getBody()->getContents(), true);
        $inputs['id'] = $args['id'] ?? $inputs['id'];

        $responseHandler = new ResponseHandler();

        // @todo moving the validation logic to a separated class/layer
        $validRequest = count($inputs) > 0;
        foreach (['id', 'name', 'description'] as $key)
            if ( !isset($inputs[$key]) || $inputs[$key] === '') $validRequest = false;

        if(!$validRequest)
            return $responseHandler
                ->type('/v1/errors/wrong_input_data')
                ->title('category_update_failure')
                ->status(400)
                ->detail('the category ['.$inputs['id'].'] was not updated, no sufficient input data provided')
                ->jsonSend();


        $category = Category::createFromArrayAssoc($inputs);
        $categoryRepo = new CategoryRepositoryByPdo();

        if ( $categoryRepo->store($category) )
            return $responseHandler
                ->type('/v1/category_updated')
                ->title('category_updated')
                ->status(200)
                ->detail('category ['.$category->name().'] successfully updated with the following data')
                ->jsonSend(["category" => $category->toMap()]);

        else return $responseHandler
                ->type('/v1/errors/category_update_failure')
                ->title('category_storing_failure')
                ->status(500)
                ->detail('the category ['.$inputs['id'].'] was not updated due to a server error')
                ->jsonSend();

    }
}