<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Controller;


use BlogPostsHandling\Api\Repository\PostsCategoriesRepositoryByPdo;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use BlogPostsHandling\Api\Repository\CategoryRepositoryByPdo;
use BlogPostsHandling\Api\Repository\PostRepositoryByPdo;

class AddCategoryToAPostController
{
    public function __invoke(Request $request, Response $response, $args): Response
    {
        $inputs = json_decode($request->getBody()->getContents(), true);
        $postId = $args['pid'] ?? $inputs['pid'] ?? null;
        $categoryId = $args['cid'] ?? $inputs['cid'] ?? null;

        $validRequest = isset($postId) && isset($categoryId);

        if ($validRequest) {
            $category = (new CategoryRepositoryByPdo)->findById($categoryId);
            $post = (new PostRepositoryByPdo)->findById($postId);
        }

        // simple validation @todo to be moved
        $details = [];
        if ($category === false) $details[] = 'No such category found. Nothing to be added.';
        if ($post === false) $details[] = 'No such post found. Nothing to be added.';

        $validRequest = $post && $category;
        if (! $validRequest) {
            return new JsonResponse([
                "message" => 'invalid input data provided',
                "msgid" => 'adding_category_to_post_failure',
                "detail" => $details
            ], 400);

        } elseif ( (new PostsCategoriesRepositoryByPdo)->store($post, $category) ) {
            return new JsonResponse([
                "message" => 'category {'.$category->name() .'} added to the post {'.$post->title().'}',
                "msgid" => 'adding_category_to_post_success',
            ], 200);
        }

        return new JsonResponse([
            "message" => 'operation failure',
            "msgid" => 'operation_failure'
        ], 400);
    }
}