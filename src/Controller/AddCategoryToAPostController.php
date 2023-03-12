<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Controller;


use BlogPostsHandling\Api\Repository\PostsCategoriesRepositoryByPdo;
use BlogPostsHandling\Api\Response\ResponseHandler;
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

        $responseHandler = new ResponseHandler();

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
            return $responseHandler
                ->type('/v1/errors/wrong_input_data')
                ->title('adding_category_to_post_failure')
                ->status(400)
                ->detail('no sufficient or invalid input data provided')
                ->jsonSend($details);

        } elseif ( (new PostsCategoriesRepositoryByPdo)->store($post, $category) ) {
            return $responseHandler
                ->type('/v1/category_added_to_a_post')
                ->title('adding_category_to_post_success')
                ->status(200)
                ->detail('category {'.$category->name() .'} added to the post {'.$post->title().'}')
                ->jsonSend();
        }

        return $responseHandler
            ->type('/v1/errors/operation_failure')
            ->title('operation_failure')
            ->status(500)
            ->detail('category {'.$category->name() .'} was not added to the post {'.$post->title().'} due to a server error')
            ->jsonSend();
    }
}