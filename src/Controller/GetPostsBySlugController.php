<?php

declare(strict_types=1);

namespace BlogPostsHandling\Api\Controller;

use DI\NotFoundException;
use BlogPostsHandling\Api\Entity\Post;
use BlogPostsHandling\Api\Repository\PostRepositoryByPdo;
use BlogPostsHandling\Api\Response\ResponseHandler;
use BlogPostsHandling\Api\Validator\{PostInputValidator,InvalidInputsException};
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetPostsBySlugController
{
    public function __invoke(Request $request, Response $response, $args): Response
    {
        /**
         * @OA\Get(
         *     path="/v1/posts/slug/{slug}",
         *     @OA\Response(response="200", description="Fetching a post data by given slug route")
         * )
         */
        $inputs = json_decode($request->getBody()->getContents(), true);
        $inputs['slug'] = $args['slug'] ?? $inputs['slug'] ?? null;

        $responseHandler = new ResponseHandler();
        $postRepository = new PostRepositoryByPdo();
        $post = null;

        if ($inputs['slug'] !== null) {
            try {
                $postInputValidator = new PostInputValidator($inputs);
                $postInputValidator->slugValidation()->sendResult();
                $post = $postRepository->findBySlug($inputs['slug']);
            } catch (NotFoundException $nfe) {
                return $responseHandler
                ->type('/v1/errors/post_slug_not_found')
                ->title('invalid_post_slug')
                ->status(404)
                ->detail($nfe->getMessage() . 'Nothing to be retrieved. ')
                ->jsonSend();
            } catch (InvalidInputsException $iie) {
                return $responseHandler
                ->type('/v1/errors/wrong_input_data')
                ->title('wrong_input_data')
                ->status(400)
                ->detail('post not updated, no sufficient or invalid input data provided')
                ->jsonSend($iie->getErrorMessages());
            }
        }

        try {
            if ($post instanceof Post) {
                return $responseHandler
                    ->type('/v1/resource_found')
                    ->title('post_found')
                    ->status(200)
                    ->detail('post [' . $post->title() . '] with slug [' . $post->slug() . '] was successfully found')
                    ->jsonSend(["post" => $post->toMap()]);
            } else {
                throw new \Error('Valid post object cannot be returned ');
            }
        } catch (\Throwable $th) {
            error_log('Error occurred -> '
                . "File: {$th->getFile()}:{$th->getLine()}, message: {$th->getMessage()}" . PHP_EOL);
            return $responseHandler
                ->type('/v1/errors/operation_failure')
                ->title('operation_failure')
                ->status(500)
                ->detail('post not found due to a server error')
                ->jsonSend();
        }
    }
}
