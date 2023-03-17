<?php

declare(strict_types=1);

namespace BlogPostsHandling\Api\Storage;

use PDO;

class DatabaseConnection implements Connectable
{
    /** @var DatabaseDataObject * */
    private DatabaseDataObject $databaseDataObject;

    public function __construct(DatabaseDataObject $dbdo)
    {
        $this->databaseDataObject = $dbdo;
    }

    public function databaseDataObject(): DatabaseDataObject
    {
        return $this->databaseDataObject;
    }

    /**  @return PDO|false  */
    public function connect(): PDO|false
    {
        try {
            $dbdo = $this->databaseDataObject();
            return new PDO((string)$dbdo, $dbdo->username(), $dbdo->password());
        } catch (\Throwable $th) {
            error_log('Error occurred -> '
                . "File: {$th->getFile()}:{$th->getLine()}, message: {$th->getMessage()}" . PHP_EOL);
            return false;
        }
    }
}
