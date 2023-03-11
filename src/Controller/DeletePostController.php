<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Controller;

use BlogPostsHandling\Api\Response\ResponseHandler;
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

        $responseHandler = new ResponseHandler();

        /** @todo to be moved in separate Validation layer  */
        if ($postId === null)
            return $responseHandler
                ->type('/v1/errors/post_not_found')
                ->title('invalid_post_id')
                ->status(400)
                ->detail('Invalid post ID provided')
                ->jsonSend(["msgid" => 'invalid_post_id']);

        $postRepository = new PostRepositoryByPdo();
        $post = $postRepository->findById($postId);

        if (! $post )
            return $responseHandler
                ->type('/v1/errors/post_id_not_found')
                ->title('invalid_post_id')
                ->status(404)
                ->detail('A post with id ['. $postId .'] was not found, nothing to be deleted')
                ->jsonSend(["msgid" => 'post_id_not_found']);

        elseif ( $postRepository->deleteById( $post->id() ) )
            return $responseHandler
                ->type('/v1/post_deleted')
                ->title('Post deleted')
                ->status(200)
                ->detail('post ['.$post->title().'] was successfully deleted')
                ->jsonSend(["post" => $post->toMap()]);

        else
            return $responseHandler
                ->type('/v1/errors/post_deletion_failure')
                ->title('invalid_post_id')
                ->status(400)
                ->detail('the post ['.$post->title().'] was not deleted')
                ->jsonSend(["msgid" => 'post_deletion_failure']);
    }
}