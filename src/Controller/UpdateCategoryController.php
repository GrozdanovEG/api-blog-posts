<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Controller;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UpdateCategoryController
{
    public function __invoke(Request $request, Response $response, $args): Response
    {
        // the logic to be implemented here
        return new JsonResponse(["message" => 'category updated']);
    }
}