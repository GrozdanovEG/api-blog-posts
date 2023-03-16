<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Repository;

use DI\NotFoundException;
use BlogPostsHandling\Api\Entity\{FileUploaded, Post, Category};

class PostRepositoryByPdo extends RepositoryByPdo implements PostRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function store(Post $post): Post|false
    {
        /** @todo To be moved to QueryBuilder */
        try {
            $this->findById( $post->id() );
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
        } catch (NotFoundException $nfe) {
            $query =<<<INSERTQ
            INSERT INTO posts (id, title, slug, content, thumbnail, author, posted_at)
                VALUES (:id, :title, :slug, :content, :thumbnail, :author, :posted_at);
            INSERTQ;
        }

        $parameters = [
            'id' => $post->id(),
            'title' => $post->title(),
            'slug' => $post->slug(),
            'content' => $post->content(),
            'thumbnail' => ($post->thumbnail() ?? null),
            'author' => $post->author(),
            'posted_at' => $post->postedAt()->format('Y-m-d H:i:s')
        ];

        if( ($this->pdo->prepare($query))->execute($parameters) ) {
            return $post;
        };

        return false;
    }

    /**
     * @inheritDoc
     * @throws NotFoundException
     */
    public function findById(string $pid): Post|false
    {
        $query =<<<QUERY
                    SELECT p.id, p.title, p.slug, p.content, p.thumbnail, p.author, p.posted_at,
                           pc.id_category AS cid, c.name  
                    FROM posts AS p 
                    LEFT JOIN posts_categories AS pc ON p.id = pc.id_post
                    LEFT JOIN categories AS c ON c.id = pc.id_category
                    WHERE p.id = :id;
                QUERY;

        $post = null;
        $stmt = $this->pdo->prepare($query);
        $parameters = ['id' => $pid];

        if( $stmt->execute($parameters) && $rows = $stmt->fetchAll() )
            if (count($rows) > 0 ) {
                $post = Post::createFromArrayAssoc($rows[0]);
                foreach ( $rows as $r ) {
                    if ($r['cid'] && $r['name'])
                    $post->addCategory(  new Category($r['cid'], $r['name'], '') );
                }
        };
        if ($post === null)
            throw new NotFoundException('A post with ID {'.$pid.'} was not found. ');

        return $post;
    }

    public function deleteById(string $pid): bool
    {
        try {
            $thumbnailFileName = $this->findById($pid)->thumbnail();
            $thumbnail = new FileUploaded('',$thumbnailFileName);
            $thumbnail->delete(__DIR__.'/../../public/');
        } catch(\Throwable $th) {
            error_log('A problem with the thumbnail file deletion occurred:: ' .
                $th->getFile() . ':' . $th->getLine() . PHP_EOL . $th->getMessage());
        }

        $query = 'DELETE FROM posts_categories WHERE id_post = :id; DELETE FROM posts WHERE id = :id';
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