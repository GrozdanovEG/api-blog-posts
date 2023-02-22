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

    /**  @return PDO|false  */    // @todo To Be replaced with NullObject
    public function connect(): PDO|false
    {
        try {
            $dbdo = $this->databaseDataObject();
            return new PDO((string)$dbdo, $dbdo->username(), $dbdo->password());

        } catch (Throwable $throwable) {
            return false;
        }
    }

}

