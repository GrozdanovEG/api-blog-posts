<?php

declare(strict_types=1);

namespace BlogPostsHandling\Api\Storage;

interface Connectable
{
    public function connect(): \PDO|false;
}
