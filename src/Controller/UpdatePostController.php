<?php

declare(strict_types=1);

namespace BlogPostsHandling\Api\Controller;

use DI\NotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use BlogPostsHandling\Api\Entity\Post;
use BlogPostsHandling\Api\Repository\PostRepositoryByPdo;
use BlogPostsHandling\Api\Response\ResponseHandler;
use BlogPostsHandling\Api\Validator\{PostInputValidator,InvalidInputsException};

class UpdatePostController
{
    public function __invoke(Request $request, Response $response, $args): Response
    {
        $inputs = json_decode($request->getBody()->getContents(), true);
        $inputs['id'] = $args['id'] ?? $inputs['id'];
        $responseHandler = new ResponseHandler();
        $postRepository = new PostRepositoryByPdo();

        try {
            $postFromRepository = $postRepository->findById($inputs['id']);
            $postInputValidator = new PostInputValidator($inputs);
            $postInputValidator
                ->populateWithObjectData($postFromRepository)
                ->minimalValidation()
                ->sendResult();
        } catch (NotFoundException $nfe) {
            return $responseHandler
                ->type('/v1/errors/post_id_not_found')
                ->title('invalid_post_id')
                ->status(404)
                ->detail($nfe->getMessage() . 'Nothing to be updated. ')
                ->jsonSend();
        } catch (InvalidInputsException $iie) {
            return $responseHandler
                ->type('/v1/errors/wrong_input_data')
                ->title('wrong_input_data')
                ->status(400)
                ->detail('post not updated, no sufficient or invalid input data provided')
                ->jsonSend($iie->getErrorMessages());
        }

        try {
            $post = Post::createFromArrayAssoc($postInputValidator->validatedFields());
            $postRepository->store($post);
                return $responseHandler
                ->type('/v1/post_updated')
                ->title('post_updated')
                ->status(200)
                ->detail('post [' . $post->title() . '] successfully updated with the following data')
                ->jsonSend(["post" => $post->toMap()]);
        } catch (\Throwable $th) {
            error_log('Error occurred -> '
                . "File: {$th->getFile()}:{$th->getLine()}, message: {$th->getMessage()}" . PHP_EOL);
            return $responseHandler
                ->type('/v1/errors/post_update_failure')
                ->title('post_update_failure')
                ->status(500)
                ->detail('the post id ' . $inputs['id'] . ' was not updated due to a server error')
                ->jsonSend();
        }
    }
}
