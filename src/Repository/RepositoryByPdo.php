<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Repository;

use DI\Container;
use PDO;

class RepositoryByPdo
{
    /** @var PDO|null  */
    protected ?PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        if ($pdo) $this->pdo = $pdo;
    }

    /** @var Container $container
      * @return RepositoryByPdo|bool   */
    public function setFromContainer(Container $container): self|false
    {
        if ($container->has('PdoDbConn')) {
            $this->pdo = $container->get('PdoDbConn');
            return $this;
        }
        return false;
    }

    public function pdo(): PDO
    {
        return $this->pdo;
    }

}