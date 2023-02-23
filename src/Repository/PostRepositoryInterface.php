<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Repository;

use BlogPostsHandling\Api\Entity\Post;

interface PostRepositoryInterface
{
    /** @var Post $post
      * @return Post|false */
    public function store(Post $post): Post|false;

    /** @return Post[] */
    public function fetchAll(): array;

    /** @var string $pid
      * @return Post|false */
    public function findById(string $pid): Post|false;
}