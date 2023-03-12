<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Controller;

use BlogPostsHandling\Api\Repository\PostRepositoryByPdo;
use BlogPostsHandling\Api\Response\ResponseHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetPostsBySlugController
{
    public function __invoke(Request $request, Response $response, $args): Response
    {
        $inputs = json_decode($request->getBody()->getContents(), true);
        $slug = $args['slug'] ?? $inputs['slug'] ?? null;

        $responseHandler = new ResponseHandler();

        $postRepository = new PostRepositoryByPdo();

        if ($post = $postRepository->findBySlug($slug)) {
            return $responseHandler
                ->type('/v1/resource_found')
                ->title('post_found')
                ->status(200)
                ->detail('post ['.$post->title().'] with slug ['. $slug .'] was successfully found')
                ->jsonSend(["post" => $post->toMap()]);

        } else return $responseHandler
                ->type('/v1/errors/post_not_found')
                ->title('post_not_found')
                ->status(404)
                ->detail('A post with slug [' . $slug . '] was not found, nothing to be retrieved')
                ->jsonSend();
    }
}

