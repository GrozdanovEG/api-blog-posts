<?php

declare(strict_types=1);

namespace BlogPostsHandling\Api\Repository;

use BlogPostsHandling\Api\Entity\Category;

interface CategoryRepositoryInterface
{
    /** @param Category $category
      * @return Category|false      */
    public function store(Category $category): Category|false;

    /** @return Category[] */
    public function fetchAll(): array;

    /** @param string $cid
      * @return Category */
    public function findById(string $cid): Category;
}
