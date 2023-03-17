<?php

declare(strict_types=1);

namespace BlogPostsHandling\Api\Repository;

use BlogPostsHandling\Api\Entity\{Post,Category};

class PostsCategoriesRepositoryByPdo extends RepositoryByPdo implements PostsCategoriesRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function store(Post $post, Category $category): bool
    {
        $query = 'INSERT INTO posts_categories (id_post, id_category) VALUES (:pid, :cid)';
        $statement = $this->pdo->prepare($query);
        $parameters = [
            'pid' => $post->id(),
            'cid' => $category->id()
        ];

        if ($statement->execute($parameters)) {
            return true;
        };
        return false;
    }
}
