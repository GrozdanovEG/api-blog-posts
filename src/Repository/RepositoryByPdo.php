<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Repository;

use DI\Container;
use PDO;

class RepositoryByPdo extends AbstractRepository
{
    /** @var PDO|null  */
    protected ?PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        if ($pdo) $this->pdo = $pdo;
        else $this->pdo = $this->setFromContainer()->pdo();
    }

    /** @var Container $container
      * @return RepositoryByPdo|bool   */
    public function setFromContainer(): self|false
    {
        if ($this->container()->has('PdoDbConn')) {
            $this->pdo = $this->container()->get('PdoDbConn');
            return $this;
        }
        return false;
    }

    public function pdo(): PDO
    {
        return $this->pdo;
    }

}