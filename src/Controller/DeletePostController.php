<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Controller;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use BlogPostsHandling\Api\Repository\PostRepositoryByPdo;

class DeletePostController
{
    public function __invoke(Request $request, Response $response, $args): Response
    {
        $inputs = json_decode($request->getBody()->getContents(), true);
        $postId = $args['id'] ?? $inputs['id'];

        /** @todo to be moved in separate Validation layer  */
        if ($postId === null) return new JsonResponse([
            "message" => 'Invalid post ID provided',
            "msgid" => 'invalid_post_id'
        ], 400);

        $postRepository = new PostRepositoryByPdo();
        $post = $postRepository->findById($postId);

        if (! $post )
            return new JsonResponse([
                "message" => 'A post with id ['. $postId .'] was not found, nothing to be deleted',
                "msgid" => 'post_id_not_found',
            ], 404);

        elseif ( $postRepository->deleteById( $post->id() ) )
            return new JsonResponse([
                "message" => 'post ['.$post->title().'] was successfully deleted',
                "msgid" => 'post_deleted',
                "post" => $post->toMap()
            ], 200);

        else return new JsonResponse([
            "message" => 'the post ['.$post->title().'] was not deleted',
            "msgid" => 'post_deletion_failure'
        ], 400);

    }
}