<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Controller;

use BlogPostsHandling\Api\Entity\Category;
use BlogPostsHandling\Api\Repository\CategoryRepositoryByPdo;
use BlogPostsHandling\Api\Response\ResponseHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AddCategoryController
{
    public function __invoke(Request $request, Response $response, $args): Response
    {
        $inputs = json_decode($request->getBody()->getContents(), true);
        $responseHandler = new ResponseHandler();

         // @todo moving and improving the validation logic to a separated class/layer
        $validRequest = count($inputs) > 0;
        foreach (['name', 'description'] as $key)
            if ( !isset($inputs[$key]) || $inputs[$key] === '') $validRequest = false;

        if(!$validRequest) return $responseHandler
            ->type('/v1/errors/wrong_input_data')
            ->title('category_creation_failure')
            ->status(400)
            ->detail('a new category cannot be created, no sufficient or invalid input data provided')
            ->jsonSend();

        $category = Category::createFromArrayAssoc($inputs);

        if (  (new CategoryRepositoryByPdo())->store($category) )
            return $responseHandler
                ->type('/v1/errors/category_added')
                ->title('new_category_added')
                ->status(201)
                ->detail('category ['.$category->name().'] successfully added')
                ->jsonSend([$category->toMap()]);

        else return $responseHandler
            ->type('/v1/errors/category_storing_failed')
            ->title('category_creation_failure')
            ->status(500)
            ->detail('the category ['.$category->name().'] was not added due to a server error')
            ->jsonSend();
    }
}