<?php

namespace BlogPostsHandling\Api\Storage;

class StorageData
{
    private array $dbData = [
        'host' => 'localhost',
        'username' => 'root',
        'password' => 'root',
        'databaseName' => '',
        'port' =>  '3306'
    ];
    private array $filenames = [];

    public function __construct(?array $env = [])
    {
        if (isset($env))
            $this->dbData = [
                'host' => ($env['DB_HOST'] ?? 'localhost'),
                'username' => $env['DB_USER'],
                'password' => $env['DB_PASS'],
                'databaseName' => $env['DB_NAME'],
                'port' =>  $env['DB_PORT'] ?? '3306'
            ];
    }

    public function dbData(): array
    {
        return $this->dbData;
    }
}