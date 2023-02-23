<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Controller;

use DI\Container;

abstract class AbstractController
{
    protected Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }
}