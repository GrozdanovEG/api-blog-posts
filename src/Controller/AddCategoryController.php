<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Controller;

use BlogPostsHandling\Api\Entity\Category;
use Laminas\Diactoros\Response\JsonResponse;
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AddCategoryController
{

    public function __invoke(Request $request, Response $response, $args): Response
    {
        $inputs = json_decode($request->getBody()->getContents(), true);
        $category = Category::createFromArrayAssoc($inputs);
        // Attempts to handle the container
        //$container = new Container();
        //$pdoConnection = $container->get('PdoDbConn');
        var_dump($category);exit;

        $query =<<<QUERY
           INSERT INTO categories (id, name, description)
               VALUES
               (:id, :name, :description);
           QUERY;


        // to be made persistent
        return new JsonResponse([
            "message" => 'category ['.$category->name().'] added',
            "msgid" => 'category_added',
            "catid" => $category->id()
        ], 201);
    }
}