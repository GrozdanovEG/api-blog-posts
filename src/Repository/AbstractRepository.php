<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Repository;

use DI\Container;
use BlogPostsHandling\Api\Factory\ContainerFactory;


abstract class AbstractRepository
{
    protected Container $container;

    public function container(): Container
    {
        if (!isset($this->container)) $this->container = (new ContainerFactory)();
        return $this->container;
    }

}