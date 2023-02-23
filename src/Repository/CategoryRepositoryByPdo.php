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
        $this->pdo = $this->setFromContainer((new ContainerFactory)())->pdo();

        if ( $this->findById( $category->id() ) )
            $query = 'UPDATE categories SET name = :name, description = :description WHERE id = :id';
        else
            $query = 'INSERT INTO categories (id, name, description) VALUES (:id, :name, :description);';

        $statement = $this->pdo->prepare($query);
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
        $c = (new ContainerFactory)();
        $this->pdo = $this->setFromContainer($c)->pdo();

        $query = 'SELECT * FROM categories WHERE 1;';

        $recordsFound = [];

        if( $statement = $this->pdo->query($query) ) {
            $results= $statement->fetchAll();
            if($results && count($results) > 0)
            foreach ($results as $rec) $recordsFound[] = Category::createFromArrayAssoc($rec);
        }
        return $recordsFound;
    }

    /**
     * @inheritDoc
     */
    public function findById(string $cid): Category|false
    {
        $c = (new ContainerFactory)();
        $this->pdo = $this->setFromContainer($c)->pdo();

        $query = 'SELECT * FROM categories WHERE id = :id';

        $statement = $this->pdo->prepare($query);
        $parameters = ['id' => $cid];

        if( $statement->execute($parameters) ) {
            $inputs = $statement->fetch();
            if($inputs && count($inputs) > 0)
                return Category::createFromArrayAssoc($inputs);
        };
        return false;
    }

    public function deleteById(string $cid): bool
    {
        $c = (new ContainerFactory)();
        $this->pdo = $this->setFromContainer($c)->pdo();

        $query = 'DELETE FROM categories WHERE id = :id';
        $statement = $this->pdo->prepare($query);
        $parameters = ['id' => $cid];

        if( $statement->execute($parameters) ) return true;

        return false;
    }
}