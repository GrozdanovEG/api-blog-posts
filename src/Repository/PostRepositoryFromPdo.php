<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Repository;

use BlogPostsHandling\Api\Entity\Post;
use PDO;

class PostRepositoryFromPdo implements PostRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @inheritDoc
     */
    public function store(Post $post): Post|false
    {
        // TODO: Implement store() method.
        return false;
    }

    /**
     * @inheritDoc
     */
    public function fetchAll(): array
    {
        // TODO: Implement fetchAll() method.
        return [];
    }

    public function findById(): Post
    {
        // TODO: Implement findById() method.
        return new Post;
    }
}