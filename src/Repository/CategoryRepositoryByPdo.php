<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Repository;

use BlogPostsHandling\Api\Entity\Category;
use DI\NotFoundException;

class CategoryRepositoryByPdo extends RepositoryByPdo implements CategoryRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function store(Category $category): Category|false
    {
        try {
            if ( $this->findById($category->id()) )
            $query = 'UPDATE categories SET name = :name, description = :description WHERE id = :id';
        } catch (NotFoundException $nfe) {
            $query = 'INSERT INTO categories (id, name, description) VALUES (:id, :name, :description);';
        }

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
     * @throws NotFoundException
     */
    public function findById(string $cid): Category
    {
        $query = 'SELECT * FROM categories WHERE id = :id';

        $statement = $this->pdo->prepare($query);
        $parameters = ['id' => $cid];
        $category = null;

        if( $statement->execute($parameters) ) {
            $inputs = $statement->fetch();
            if($inputs && count($inputs) > 0)
                $category = Category::createFromArrayAssoc($inputs);
        };

        if ($category === null)
            throw new NotFoundException('A category with ID {'.$cid.'} was not found. ');

        return $category;
    }

    public function deleteById(string $cid): bool
    {
        $query = 'DELETE FROM categories WHERE id = :id';
        $statement = $this->pdo->prepare($query);
        $parameters = ['id' => $cid];

        if( $statement->execute($parameters) ) return true;

        return false;
    }
}