<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Controller;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use BlogPostsHandling\Api\Response\ResponseHandler;
use BlogPostsHandling\Api\Repository\PostRepositoryByPdo;

class GetPostController
{
    public function __invoke(Request $request, Response $response, $args): Response
    {
        $inputs = json_decode($request->getBody()->getContents(), true);
        $postId = $args['id'] ?? $inputs['id'] ?? null;
        $responseHandler = new ResponseHandler();

        if ( $post = (new PostRepositoryByPdo)->findById($postId) ) {
            return $responseHandler
                    ->type('/v1/resource_found')
                    ->title('post_found')
                    ->status(200)
                    ->detail('post ['.$post->title().'] was successfully found')
                    ->jsonSend(["post" => $post->toMap()]);
        }

        else return $responseHandler
                    ->type('/v1/errors/post_not_found')
                    ->title('post_not_found')
                    ->status(404)
                    ->detail('A post with id ['. $postId .'] was not found, nothing to be retrieved')
                    ->jsonSend();
    }
}