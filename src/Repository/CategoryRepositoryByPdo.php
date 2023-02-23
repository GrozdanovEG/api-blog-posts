<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Repository;

use BlogPostsHandling\Api\Entity\Category;
use BlogPostsHandling\Api\Factory\ContainerFactory;

class CategoryRepositoryByPdo extends RepositoryByPdo implements CategoryRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function store(Category $category): Category|false
    {
        $pdoConnection = $this->setFromContainer((new ContainerFactory)())->pdo();

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

        if( $statement->execute($parameters) ) {
            return $category;
        };
        return false;
    }

    /**
     * @inheritDoc
     */
    public function fetchAll(): array
    {
        // TODO: Implement fetchAll() method.
    }

    /**
     * @inheritDoc
     */
    public function findById(string $cid): Category|false
    {
        // TODO: Implement findById() method.
    }
}