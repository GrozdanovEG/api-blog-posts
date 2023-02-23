<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Factory;

use DI\Container;

class ContainerFactory
{
    public function __invoke(): Container
    {
        $container = new Container();

        $container->set('PdoDbConn', static function() {
            return (new PdoConnectionFactory)();
        });

        return $container;
    }
}