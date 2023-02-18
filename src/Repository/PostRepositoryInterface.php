<?php
declare(strict_types=1);

namespace BlogPostsHandling\Api\Repository;

use BlogPostsHandling\Api\Entity\Post;

interface PostRepositoryInterface
{
    /** @var Post $post */
    public function store(Post $post): Post|false;
    /** @return Post[] */
    public function fetchAll(): array;
    public function findById(): Post;
}