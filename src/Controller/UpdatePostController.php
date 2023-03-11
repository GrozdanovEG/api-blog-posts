<?php

namespace BlogPostsHandling\Api\Controller;

use BlogPostsHandling\Api\Response\ResponseHandler;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use BlogPostsHandling\Api\Entity\Post;
use BlogPostsHandling\Api\Repository\PostRepositoryByPdo;

class UpdatePostController
{
    public function __invoke(Request $request, Response $response, $args): Response
    {
        $inputs = json_decode($request->getBody()->getContents(), true);
        $inputs['id'] = $args['id'] ?? $inputs['id'];
        $responseHandler = new ResponseHandler();

        // @todo moving the validation logic to a separated class/layer
        $validRequest = count($inputs) > 0;
        foreach (['id', 'title', 'author', 'content', 'slug'] as $key)
            if ( !isset($inputs[$key]) || $inputs[$key] === '') $validRequest = false;

        if(!$validRequest)
            return $responseHandler
                ->type('/v1/errors/wrong_input_data')
                ->title('wrong_input_data')
                ->status(400)
                ->detail('the post ['.$inputs['id'].'] was not updated, no sufficient or invalid input data provided')
                ->jsonSend();


        $post = Post::createFromArrayAssoc($inputs);
        $postRepository = new PostRepositoryByPdo();

        $postFromRepository = $postRepository->findById( $post->id() );

        if ( $postFromRepository === false )
            return $responseHandler
                ->type('/v1/errors/post_id_not_found')
                ->title('invalid_post_id')
                ->status(404)
                ->detail('a post with id ['.$post->id().'] was not found, nothing to be updated')
                ->jsonSend(["post" => $post->toMap()]);

        elseif ( $postRepository->store($post) )
            return $responseHandler
                ->type('/v1/post_updated')
                ->title('post_updated')
                ->status(200)
                ->detail('post ['.$post->title().'] successfully updated with the following data')
                ->jsonSend(["post" => $post->toMap()]);

        else return $responseHandler
            ->type('/v1/errors/post_update_failure')
            ->title('post_update_failure')
            ->status(400)
            ->detail('the post ['.$post->title().'] was not updated')
            ->jsonSend(["post" => $post->toMap()]);
    }
}