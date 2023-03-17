<?php

declare(strict_types=1);

namespace BlogPostsHandling\Api\Controller;

use DI\NotFoundException;
use BlogPostsHandling\Api\Entity\Post;
use BlogPostsHandling\Api\Validator\{PostInputValidator,InvalidInputsException};
use BlogPostsHandling\Api\Response\ResponseHandler;
use BlogPostsHandling\Api\Repository\PostRepositoryByPdo;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetPostController
{
    public function __invoke(Request $request, Response $response, $args): Response
    {
        $inputs = json_decode($request->getBody()->getContents(), true);
        $inputs['id'] = $args['id'] ?? $inputs['id'];
        $responseHandler = new ResponseHandler();
        $postRepository = new PostRepositoryByPdo();

        try {
            $postInputValidator = new PostInputValidator($inputs);
            $postInputValidator->minimalValidation()->sendResult();
            $post = $postRepository->findById($inputs['id']);
        } catch (InvalidInputsException $iie) {
            return $responseHandler
                ->type('/v1/errors/wrong_input_data')
                ->title('wrong_input_data')
                ->status(400)
                ->detail('the post [' . $inputs['id'] . '] was not retrieved, no sufficient or invalid input data provided')
                ->jsonSend($iie->getErrorMessages());
        } catch (NotFoundException $nfe) {
            return $responseHandler
                ->type('/v1/errors/post_id_not_found')
                ->title('invalid_post_id')
                ->status(404)
                ->detail($nfe->getMessage() . 'Nothing to be retrieved. ')
                ->jsonSend();
        }

        try {
            if ($post instanceof Post) {
                return $responseHandler
                    ->type('/v1/resource_found')
                    ->title('post_found')
                    ->status(200)
                    ->detail('post [' . $post->title() . '] was successfully found')
                    ->jsonSend(["post" => $post->toMap()]);
            } else
                throw new \Error('Valid post object cannot be returned ');
        } catch (\Throwable $th) {
            error_log('Error occurred -> '
                . "File: {$th->getFile()}:{$th->getLine()}, message: {$th->getMessage()}" . PHP_EOL);
            return $responseHandler
                ->type('/v1/errors/post_cannot_be_fetched')
                ->title('post_cannot_be_fetched')
                ->status(500)
                ->detail('A post with id [' . $inputs['id'] . '] not found for unknown reason, nothing to be retrieved')
                ->jsonSend();
        }
    }
}
