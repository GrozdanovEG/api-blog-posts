<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Controller;

use BlogPostsHandling\Api\Entity\Category;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AddCategoryController extends AbstractController
{
    public function __invoke(Request $request, Response $response, $args): Response
    {
        $inputs = json_decode($request->getBody()->getContents(), true);
        $category = Category::createFromArrayAssoc($inputs);

        $pdoConnection = null;
        if ($this->container->has('PdoDbConn')) {
            $pdoConnection = $this->container?->get('PdoDbConn');
        }

        // Processing object storing logic
        $query =<<<QUERY
           INSERT INTO categories (id, name, description)
               VALUES
               (:id, :name, :description);
           QUERY;
        $statement = $pdoConnection->prepare($query);
        $parameters = [
            'id' => $category->id(),
            'name' => $category->name(),
            'description' => $category->description()
        ];
        $statement->execute($parameters);

        return new JsonResponse([
            "message" => 'category ['.$category->name().'] added',
            "msgid" => 'category_added',
            "catid" => $category->id()
        ], 201);
    }
}