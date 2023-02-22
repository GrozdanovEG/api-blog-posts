<?php

namespace BlogPostsHandling\Api\Storage;

class StorageData
{
    private array $databaseData = [
        'host' => 'localhost',
        'username' => 'root',
        'password' => 'root',
        'databaseName' => '',
        'port' =>  '3306'
    ];
    private array $filenames = [];

    public function __construct(?array $env = [])
    {
        if (isset($env)) {
            $this->databaseData = [
                'databaseType' => ($env['DB_TYPE'] ?? 'mysql'),
                'host' => ($env['DB_HOST'] ?? 'localhost'),
                'username' => $env['DB_USER'],
                'password' => $env['DB_PASS'],
                'databaseName' => $env['DB_NAME'],
                'port' =>  $env['DB_PORT'] ?? '3306'
            ];
            /* $this->filenames to be populated if filesystem is used for storage  */
        }
    }

    public function databaseData(): array
    {
        return $this->databaseData;
    }

    public function filesystemData(): array
    {
        return $this->filenames;
    }

    /** @return array    */
    public function allStorageData(): array
    {
        return [$this->databaseData(), $this->filesystemData()];
    }
}