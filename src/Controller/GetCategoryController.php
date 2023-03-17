<?php

declare(strict_types=1);

namespace BlogPostsHandling\Api\Controller;

use DI\NotFoundException;
use BlogPostsHandling\Api\Entity\Category;
use BlogPostsHandling\Api\Repository\CategoryRepositoryByPdo;
use BlogPostsHandling\Api\Response\ResponseHandler;
use BlogPostsHandling\Api\Validator\{InvalidInputsException,CategoryInputValidator};
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetCategoryController
{
    public function __invoke(Request $request, Response $response, $args): Response
    {
        $inputs = json_decode($request->getBody()->getContents(), true);
        $inputs['id'] = $args['id'] ?? $inputs['id'];

        $responseHandler = new ResponseHandler();
        $categoryRepository = new CategoryRepositoryByPdo();

        try {
            $categoryInputValidator = new CategoryInputValidator($inputs);
            $categoryInputValidator->minimalValidation()->sendResult();
            $category = $categoryRepository->findById($inputs['id']);
        } catch (InvalidInputsException $iie) {
            return $responseHandler
                ->type('/v1/errors/wrong_input_data')
                ->title('wrong_input_data')
                ->status(400)
                ->detail('A category [' . $inputs['id'] . '] was not found, insufficient/invalid input data provided')
                ->jsonSend($iie->getErrorMessages());
        } catch (NotFoundException $nfe) {
            error_log($nfe->getMessage() . PHP_EOL);
            return $responseHandler
                ->type('/v1/errors/category_not_found')
                ->title('category_not_found')
                ->status(404)
                ->detail('A category with id [' . $inputs['id'] . '] was not found, nothing to be retrieved')
                ->jsonSend();
        }

        try {
            return $responseHandler
                ->type('/v1/category_found')
                ->title('category_found')
                ->status(200)
                ->detail('category {' . $category->name() . '} was successfully found')
                ->jsonSend(['category' => $category->toMap()]);
        } catch (\Throwable $th) {
            error_log('Error occurred -> ' .
                "File: {$th->getFile()}:{$th->getLine()}, message: {$th->getMessage()}" . PHP_EOL);

            return $responseHandler
                ->type('/v1/errors/category_cannot_be_fetched')
                ->title('category_cannot_be_fetched')
                ->status(500)
                ->detail('A category cannot be fetched due to a server error. ')
                ->jsonSend();
        }
    }
}
