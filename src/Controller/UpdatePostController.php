<?php

namespace BlogPostsHandling\Api\Controller;

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

        // @todo moving the validation logic to a separated class/layer
        $validRequest = count($inputs) > 0;
        foreach (['id', 'title', 'author', 'content', 'slug'] as $key)
            if ( !isset($inputs[$key]) || $inputs[$key] === '') $validRequest = false;

        if(!$validRequest)
            return new JsonResponse([
                "message" => 'the post ['.$inputs['id'].'] was not updated, no sufficient input data provided',
                "msgid" => 'post_update_failed',
                "detail" => 'wrong_input_data'
            ], 400);

        $post = Post::createFromArrayAssoc($inputs);
        $postRepository = new PostRepositoryByPdo();

        $postFromRepository = $postRepository->findById( $post->id() );

        if ( $postFromRepository === false )
            return new JsonResponse([
                "message" => 'a post with id ['.$post->id().'] was not found, nothing to be updated',
                "msgid" => 'post_id_not_found',
                "detail" => 'invalid_post_id'
            ], 404);

        elseif ( $postRepository->store($post) )
            return new JsonResponse([
                "message" => 'post ['.$post->title().'] successfully updated with the following data',
                "msgid" => 'post_updated',
                "post" => $post->toMap()
            ], 200);

        else return new JsonResponse([
            "message" => 'the post ['.$post->title().'] was not updated',
            "msgid" => 'post_update_failed'
        ], 400);
    }
}