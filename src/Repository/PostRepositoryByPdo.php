<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Repository;

use BlogPostsHandling\Api\Entity\Post;

class PostRepositoryByPdo extends RepositoryByPdo implements PostRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function store(Post $post): Post|false
    {
        /** @to be implemented QueryBuilder */
        if ( $this->findById( $post->id() ) ) {
            $query =<<<UPDATEQ
                UPDATE posts SET 
                    title = :title, 
                    slug = :slug,
                    content = :content,
                    thumbnail = :thumbnail,
                    author = :author,
                    posted_at = :posted_at                  
                    WHERE id = :id
            UPDATEQ;
        } else {
            $query =<<<INSERTQ
            INSERT INTO posts (id, title, slug, content, thumbnail, author, posted_at)
                VALUES (:id, :title, :slug, :content, :thumbnail, :author, :posted_at);
            INSERTQ;
        }

        $statement = $this->pdo->prepare($query);
        $parameters = [
            'id' => $post->id(),
            'title' => $post->title(),
            'slug' => $post->slug(),
            'content' => $post->content(),
            'thumbnail' => ($post->thumbnail() ?? null),
            'author' => $post->author(),
            'posted_at' => $post->postedAt()->format('Y-m-d H:i:s')
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
        $query ='SELECT * FROM posts WHERE id = :id';
        $statement = $this->pdo->prepare($query);
        $parameters = ['id' => $pid];

        if( $statement->execute($parameters) ) {
            $inputs = $statement->fetch();
            if($inputs && count($inputs) > 0)
                return Post::createFromArrayAssoc($inputs);
        };
        return false;
    }

    public function deleteById(string $pid): bool
    {
        $query = 'DELETE FROM posts WHERE id = :id';
        $statement = $this->pdo->prepare($query);
        $parameters = ['id' => $pid];

        if( $statement->execute($parameters) ) return true;

        return false;
    }

    /**
     * @inheritDoc
     */
    public function findBySlug(string $slug): Post|false
    {
        $query ='SELECT * FROM posts WHERE slug = :slug';
        $statement = $this->pdo->prepare($query);
        $parameters = ['slug' => $slug];

        if( $statement->execute($parameters) ) {
            $inputs = $statement->fetch();
            if($inputs && count($inputs) > 0)
                return Post::createFromArrayAssoc($inputs);
        };
        return false;
    }
}