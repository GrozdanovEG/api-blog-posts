<?php

declare(strict_types=1);

namespace BlogPostsHandling\Api\Controller;

use DI\NotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use BlogPostsHandling\Api\Repository\{CategoryRepositoryByPdo,PostRepositoryByPdo};
use BlogPostsHandling\Api\Repository\PostsCategoriesRepositoryByPdo;
use BlogPostsHandling\Api\Response\ResponseHandler;
use BlogPostsHandling\Api\Validator\{PostCategoryInputValidator,InvalidInputsException};

class AddCategoryToAPostController
{
    /**
     * @OA\Post(
     *     path="/v1/post/{pid}/addto/{cid}",
     *     @OA\Response(response="201", description="Adding a category to a post route")
     * )
     */
    public function __invoke(Request $request, Response $response, $args): Response
    {
        $inputs = json_decode($request->getBody()->getContents(), true);
        $inputs['pid'] = $args['pid'] ?? $inputs['pid'] ?? null;
        $inputs['cid'] = $args['cid'] ?? $inputs['cid'] ?? null;

        $responseHandler = new ResponseHandler();
        $categoryRepository = new CategoryRepositoryByPdo();
        $postRepository = new PostRepositoryByPdo();

        try {
            $postCategoryInputValidator = new PostCategoryInputValidator($inputs);
            $postCategoryInputValidator->minimalValidation()->sendResult();
            $category = $categoryRepository->findById($inputs['cid']);
            $post = $postRepository->findById($inputs['pid']);
        } catch (InvalidInputsException $iie) {
            return $responseHandler
                ->type('/v1/errors/wrong_input_data')
                ->title('wrong_input_data')
                ->status(400)
                ->detail('no sufficient or invalid input data provided')
                ->jsonSend($iie->getErrorMessages());
        } catch (NotFoundException $nfe) {
            return $responseHandler
                ->type('/v1/errors/entity_not_found')
                ->title('entity_not_found')
                ->status(404)
                ->detail($nfe->getMessage())
                ->jsonSend();
        }

        try {
            (new PostsCategoriesRepositoryByPdo())->store($post, $category);
                return $responseHandler
                    ->type('/v1/category_added_to_a_post')
                    ->title('adding_category_to_post_success')
                    ->status(200)
                    ->detail('category {' . $category->name() . '} added to the post {' . $post->title() . '}')
                    ->jsonSend();
        } catch (\Throwable $th) {
            error_log('Error occurred -> ' .
                "File: {$th->getFile()}:{$th->getLine()}, message: {$th->getMessage()}" . PHP_EOL);
            return $responseHandler
                ->type('/v1/errors/operation_failure')
                ->title('operation_failure')
                ->status(500)
                ->detail('a category was not added to a post due to a server error')
                ->jsonSend();
        }
    }
}
