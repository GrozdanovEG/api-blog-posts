<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Factory;

use BlogPostsHandling\Api\Storage\StorageData;
use BlogPostsHandling\Api\Storage\DatabaseDataObject;
use BlogPostsHandling\Api\Storage\DatabaseConnection;
use \PDO;

class PdoConnectionFactory
{
    public function __invoke(): PDO|false {

        try {
            $dbdo = new DatabaseDataObject( (new StorageData($_ENV))->databaseData() );
            $pdoConnection = ( new DatabaseConnection($dbdo) )->connect();

            $pdoConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdoConnection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            return $pdoConnection;
        } catch (\Exception $ex) {
            error_log($ex->getMessage());
        }
        return false;
    }
}