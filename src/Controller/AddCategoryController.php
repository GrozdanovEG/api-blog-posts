<?php

declare(strict_types=1);

namespace BlogPostsHandling\Api\Controller;

use BlogPostsHandling\Api\Entity\Category;
use BlogPostsHandling\Api\Repository\CategoryRepositoryByPdo;
use BlogPostsHandling\Api\Response\ResponseHandler;
use BlogPostsHandling\Api\Validator\{InvalidInputsException,CategoryInputValidator};
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AddCategoryController
{
    public function __invoke(Request $request, Response $response, $args): Response
    {
        $inputs = json_decode($request->getBody()->getContents(), true);

        $responseHandler = new ResponseHandler();
        $categoryRepository = new CategoryRepositoryByPdo();

        try {
            $categoryInputValidator = new CategoryInputValidator($inputs);
            $categoryInputValidator->defaultValidation()->sendResult();
        } catch (InvalidInputsException $iie) {
            return $responseHandler
                ->type('/v1/errors/wrong_input_data')
                ->title('wrong_input_data')
                ->status(400)
                ->detail('A new category cannot be added, no sufficient or invalid input data provided')
                ->jsonSend($iie->getErrorMessages());
        }

        $category = Category::createFromArrayAssoc($inputs);

        try {
            if ($categoryRepository->store($category)) {
                return $responseHandler
                    ->type('/v1/errors/category_added')
                    ->title('new_category_added')
                    ->status(201)
                    ->detail('category [' . $category->name() . '] successfully added')
                    ->jsonSend([$category->toMap()]);
            }
        } catch (\Throwable $th) {
            error_log('Error occurred -> ' . "File: {$th->getFile()}:{$th->getLine()}, message: {$th->getMessage()}" . PHP_EOL);
            return $responseHandler
                ->type('/v1/errors/category_storing_failed')
                ->title('category_creation_failure')
                ->status(500)
                ->detail('the category [' . $category->name() . '] was not added due to a server error')
                ->jsonSend();
        }
    }
}
