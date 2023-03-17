<?php

declare(strict_types=1);

namespace BlogPostsHandling\Api\Repository;

use BlogPostsHandling\Api\Entity\Post;

interface PostRepositoryInterface
{
    /** @param Post $post
      * @return Post|false */
    public function store(Post $post): Post|false;

    /** @param string $pid
      * @return Post|false */
    public function findById(string $pid): Post|false;

    /** @param string $slug
     * @return Post|false */
    public function findBySlug(string $slug): Post|false;
}
