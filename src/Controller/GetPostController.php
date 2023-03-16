<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Controller;

use BlogPostsHandling\Api\Entity\Post;
use BlogPostsHandling\Api\Validator\InvalidInputsException;
use BlogPostsHandling\Api\Validator\PostInputValidator;
use DI\NotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use BlogPostsHandling\Api\Response\ResponseHandler;
use BlogPostsHandling\Api\Repository\PostRepositoryByPdo;

class GetPostController
{
    public function __invoke(Request $request, Response $response, $args): Response
    {
        $inputs = json_decode($request->getBody()->getContents(), true);
        $inputs['id'] = $args['id'] ?? $inputs['id'] ?? null;
        $responseHandler = new ResponseHandler();
        $postRepository = new PostRepositoryByPdo();

        $validRequest = isset($inputs['id']);
        $post = null;

        if ($validRequest)
        try {
            $postInputValidator = new PostInputValidator($inputs);
            $postInputValidator->minimalValidation()->sendResult();
            $post = $postRepository->findById( $inputs['id'] );
        } catch (NotFoundException $nfe) {
            return $responseHandler
                ->type('/v1/errors/post_id_not_found')
                ->title('invalid_post_id')
                ->status(404)
                ->detail($nfe->getMessage() . 'Nothing to be retrieved. ')
                ->jsonSend();
        }
        if ( $post instanceof Post) {
            return $responseHandler
                    ->type('/v1/resource_found')
                    ->title('post_found')
                    ->status(200)
                    ->detail('post ['.$post->title().'] was successfully found')
                    ->jsonSend(["post" => $post->toMap()]);
        }

        return $responseHandler
                    ->type('/v1/errors/post_cannot_be_fetched')
                    ->title('post_cannot_be_fetched')
                    ->status(500)
                    ->detail('A post with id ['. $inputs['id'] .'] not found for unknown reason, nothing to be retrieved')
                    ->jsonSend();
    }
}