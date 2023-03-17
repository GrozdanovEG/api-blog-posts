<?php

declare(strict_types=1);

namespace BlogPostsHandling\Api\Repository;

use BlogPostsHandling\Api\Entity\Category;

interface CategoryRepositoryInterface
{
    /** @var Category $category
      * @return Category|false      */
    public function store(Category $category): Category|false;

    /** @return Category[] */
    public function fetchAll(): array;

    /** @var string $cid
      * @return Category */
    public function findById(string $cid): Category;
}
