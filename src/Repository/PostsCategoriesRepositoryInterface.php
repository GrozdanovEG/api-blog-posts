<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Repository;

use BlogPostsHandling\Api\Entity\{Category,Post};

interface PostsCategoriesRepositoryInterface
{
    /** @var Post $post
     *  @var Category $category
     *  @return bool
     */
    public function store(Post $post, Category $category): bool;

    /** @var Post $post
     *  @var Category $category
     *  @return bool
     */
    public function delete(Post $post, Category $category): bool;
}