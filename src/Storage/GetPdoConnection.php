<?php
declare(strict_types=1);

namespace BlogPostsHandling\Api\Storage;

use PDO;

class GetPdoConnection
{
    public function __invoke(): PDO|false {

        try {
            $pdo = new PDO();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $pdo;
        } catch (\Exception $ex) {
            //
        }
        return false;
    }
}