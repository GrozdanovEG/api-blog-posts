<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Controller;

use BlogPostsHandling\Api\Entity\Category;
use BlogPostsHandling\Api\Repository\CategoryRepositoryByPdo;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UpdateCategoryController extends AbstractController
{
    public function __invoke(Request $request, Response $response, $args): Response
    {
        $inputs = json_decode($request->getBody()->getContents(), true);
        $category = Category::createFromArrayAssoc($inputs);

        $categoryRepo = new CategoryRepositoryByPdo();

        if (  $categoryRepo->store($category) )
            return new JsonResponse([
                "message" => 'category ['.$category->name().'] successfully updated with the following data',
                "msgid" => 'category_updated',
                "catid" => $category->id(),
                "catname" => $category->name(),
                "catdescr" => $category->description()
            ], 200);

        else return new JsonResponse([
            "message" => 'the category ['.$category->name().'] was not updated',
            "msgid" => 'category_storing_failed'
        ], 400);
    }
}