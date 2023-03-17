<?php

declare(strict_types=1);

namespace BlogPostsHandling\Api\Repository;

use BlogPostsHandling\Api\Entity\{Category,Post};

interface PostsCategoriesRepositoryInterface
{
    /** @param Post $post
     *  @param Category $category
     *  @return bool
     */
    public function store(Post $post, Category $category): bool;
}
