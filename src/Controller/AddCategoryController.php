<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Controller;

use BlogPostsHandling\Api\Entity\Category;
use BlogPostsHandling\Api\Repository\CategoryRepositoryByPdo;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AddCategoryController extends AbstractController
{
    public function __invoke(Request $request, Response $response, $args): Response
    {
        $inputs = json_decode($request->getBody()->getContents(), true);
        $category = Category::createFromArrayAssoc($inputs);

        if (  (new CategoryRepositoryByPdo())->store($category) )
            return new JsonResponse([
                "message" => 'category ['.$category->name().'] successfully added',
                "msgid" => 'category_added',
                "catid" => $category->id(),
                "catname" => $category->name(),
                "catdescr" => $category->description()
            ], 201);

        else return new JsonResponse([
                "message" => 'the category ['.$category->name().'] was not added',
                "msgid" => 'category_storing_failed'
            ], 400);
    }
}