<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Controller;

use BlogPostsHandling\Api\Response\ResponseHandler;
use BlogPostsHandling\Api\Validator\CategoryInputValidator;
use BlogPostsHandling\Api\Validator\InvalidInputsException;
use DI\NotFoundException;
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
        $categoryRepository = new CategoryRepositoryByPdo();

        try {
            $categoryInputValidator = new CategoryInputValidator($inputs);
            $categoryFromRepository = $categoryRepository->findById($inputs['id']);
            $categoryInputValidator
                ->populateWithObjectData($categoryFromRepository)
                ->minimalValidation()
                ->sendResult();

        } catch (InvalidInputsException $iie) {
            return $responseHandler
                ->type('/v1/errors/wrong_input_data')
                ->title('wrong_input_data')
                ->status(400)
                ->detail('A new category cannot be added, no sufficient or invalid input data provided')
                ->jsonSend($iie->getErrorMessages());

        }  catch (NotFoundException $nfe) {
            error_log($nfe->getMessage() . PHP_EOL);
            return $responseHandler
                ->type('/v1/errors/category_not_found')
                ->title('category_not_found')
                ->status(404)
                ->detail('A category with id ['. $inputs['id'] .'] was not found, nothing to be retrieved')
                ->jsonSend();
        }

        $category = Category::createFromArrayAssoc($categoryInputValidator->validatedFields());

        try {
            if ($categoryRepository->store($category))
            return $responseHandler
                ->type('/v1/category_updated')
                ->title('category_updated')
                ->status(200)
                ->detail('category ['.$category->name().'] successfully updated with the following data')
                ->jsonSend(["category" => $category->toMap()]);

        }

        catch (\Throwable $th) {
            error_log('Error occurred -> ' . "File: {$th->getFile()}:{$th->getLine()}, message: {$th->getMessage()}".PHP_EOL);
            return $responseHandler
                ->type('/v1/errors/category_update_failure')
                ->title('category_storing_failure')
                ->status(500)
                ->detail('the category ['.$inputs['id'].'] was not updated due to a server error')
                ->jsonSend();
        }

    }
}