<?php
declare(strict_types=1);

namespace BlogPostsHandling\Api\Controller;

use BlogPostsHandling\Api\Entity\Post;
use BlogPostsHandling\Api\Repository\PostRepositoryByPdo;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use OpenApi\Annotations as OA;

class AddPostController
{
    public function __invoke(Request $request, Response $response, $args): Response
    {
        $inputs = json_decode($request->getBody()->getContents(), true);

        // @todo moving and improving the validation logic to a separated class/layer
        $validRequest = count($inputs) > 0;
        foreach (['title', 'author', 'content'] as $key)
            if ( !isset($inputs[$key]) || $inputs[$key] === '') $validRequest = false;

        if(!$validRequest) return new JsonResponse([
            "message" => 'a new post cannot be created, no sufficient input data provided',
            "msgid" => 'post_creation_failed',
            "detail" => 'wrong_input_data'
        ], 400);

        $post = Post::createFromArrayAssoc($inputs);

        if (  (new PostRepositoryByPdo())->store($post)
        //  storage logic to be added here for initial category if $inputs['category'] is set
        )
            return new JsonResponse([
                "message" => 'post ['.$post->title().'] successfully added',
                "msgid" => 'post_added',
                "post" => $post->toMap()
            ], 201);


        return new JsonResponse([$post->toMap()]);
    }

}
