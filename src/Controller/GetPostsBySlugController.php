<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Controller;

use BlogPostsHandling\Api\Repository\PostRepositoryByPdo;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetPostsBySlugController
{
    public function __invoke(Request $request, Response $response, $args): Response
    {
        $inputs = json_decode($request->getBody()->getContents(), true);
        $slug = $args['slug'] ?? $inputs['slug'] ?? null;

        $postRepository = new PostRepositoryByPdo();

        if ($post = $postRepository->findBySlug($slug)) {
            return new JsonResponse([
                "message" => 'post [' . $post->title() . '] was successfully found',
                "msgid" => 'post_found',
                "post" => $post->toMap()
            ], 200);
        } else return new JsonResponse([
            "message" => 'A post with slug [' . $slug . '] was not found, nothing to be retrieved',
            "msgid" => 'post_not_found'
        ],
            404);
    }
}

