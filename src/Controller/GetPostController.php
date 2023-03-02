<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Controller;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use BlogPostsHandling\Api\Repository\PostRepositoryByPdo;

class GetPostController
{
    public function __invoke(Request $request, Response $response, $args): Response
    {
        $inputData = json_decode($request->getBody()->getContents(), true);
        $postId = $args['id'] ?? $inputData['id'] ?? null;

        $postRepo = new PostRepositoryByPdo();

        if ( $post = $postRepo->findById($postId) ) {
            return new JsonResponse([
                "message" => 'post ['.$post->title().'] was successfully found',
                "msgid" => 'post_found',
                "post" => $post->toMap()
            ]);
        }
        else return new JsonResponse([
            "message" => 'A post with id ['. $postId .'] was not found, nothing to be retrieved',
            "msgid" => 'post_not_found'
        ],
            404);
    }
}