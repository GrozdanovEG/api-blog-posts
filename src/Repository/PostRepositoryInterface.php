<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Repository;

use BlogPostsHandling\Api\Entity\Post;

interface PostRepositoryInterface
{
    /** @var Post $post
      * @return Post|false */
    public function store(Post $post): Post|false;

    /** @var string $slug
     * @return Post|false */
    public function findBySlug(string $slug): Post|false;

    /** @var string $pid
      * @return Post|false */
    public function findById(string $pid): Post|false;
}