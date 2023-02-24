<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Repository;

use BlogPostsHandling\Api\Entity\Post;
use PDO;

class PostRepositoryByPdo extends RepositoryByPdo implements  PostRepositoryInterface
{


    /**
     * @inheritDoc
     */
    public function store(Post $post): Post|false
    {
        /** @to be implemented QueryBuilder */
        $query = 'INSERT INTO posts ...';

        $statement = $this->pdo->prepare($query);
        $parameters = [
            'id' => $post->id(),
            'title' => $post->title(),
            'slug' => $post->slug(),
            'content' => $post->content(),
            'thumbnail' => $post->thumbnail(),
            'author' => $post->author(),
            'posted_at' => $post->postedAt()
        ];

        if( $statement->execute($parameters) ) {
            return $post;
        };
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

    /**
     * @inheritDoc
     */
    public function findById(string $pid): Post|false
    {
        // TODO: Implement findById() method.
        return new Post;
    }
}