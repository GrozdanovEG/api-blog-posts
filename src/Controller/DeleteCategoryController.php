<?php

declare(strict_types=1);

namespace BlogPostsHandling\Api\Controller;

use DI\NotFoundException;
use BlogPostsHandling\Api\Response\ResponseHandler;
use BlogPostsHandling\Api\Repository\CategoryRepositoryByPdo;
use BlogPostsHandling\Api\Validator\{CategoryInputValidator,InvalidInputsException};
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DeleteCategoryController
{
    /**
     * @OA\Delete(
     *     path="/v1/delete/category/{id}",
     *     @OA\Response(response="200", description="Deleting a category from the blog by given id route")
     * )
     */
    public function __invoke(Request $request, Response $response, $args): Response
    {
        $inputs = json_decode($request->getBody()->getContents(), true);
        $inputs['id'] = $args['id'] ?? $inputs['id'] ?? null;

        $responseHandler = new ResponseHandler();
        $categoryRepository = new CategoryRepositoryByPdo();

        try {
            $categoryInputValidator = new CategoryInputValidator($inputs);
            $categoryInputValidator->minimalValidation()->sendResult();
            $category = $categoryRepository->findById($inputs['id']);
        } catch (NotFoundException $nfe) {
            error_log($nfe->getMessage() . PHP_EOL);
            return $responseHandler
                ->type('/v1/errors/category_not_found')
                ->title('category_not_found')
                ->status(404)
                ->detail('A category with id {' . $inputs['id'] . '} was not found, nothing to be deleted')
                ->jsonSend();
        } catch (InvalidInputsException $iie) {
            return $responseHandler
                ->type('/v1/errors/wrong_input_data')
                ->title('wrong_input_data')
                ->status(400)
                ->detail('Category not found, no sufficient or invalid input data provided')
                ->jsonSend($iie->getErrorMessages());
        }

        try {
            if ($categoryRepository->deleteById($category->id())) {
                return $responseHandler
                    ->type('/v1/category_deleted')
                    ->title('category_deleted')
                    ->status(200)
                    ->detail('category [' . $category->name() . '] was successfully deleted')
                    ->jsonSend([$category->toMap()]);
            } else {
                throw new \Error('No valid category object to be deleted ');
            }
        } catch (\Throwable $th) {
            error_log('Error occurred -> '
                . "File: {$th->getFile()}:{$th->getLine()}, message: {$th->getMessage()}" . PHP_EOL);
            return $responseHandler
                ->type('/v1/errors/category_deletion_failure')
                ->title('category_deletion_failure')
                ->status(500)
                ->detail('the category [' . $category->name() . '] was not deleted due to a server error')
                ->jsonSend([$category->toMap()]);
        }
    }
}
