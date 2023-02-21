<?php

namespace BlogPostsHandling\Api\Storage;

class DatabaseData
{
    private string $host;
    private string $username;
    private string $password;
    private string $databaseName;
    private string $port;

    public function __construct(array $dbData)
    {
        $this->host = $dbData['host'];
        $this->username = $dbData['username'];
        $this->password = $dbData['password'];
        $this->databaseName = $dbData['databaseName'];
        $this->port = $dbData['port'];
    }

    public function host(): string
    {
        return $this->host;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function databaseName(): string
    {
        return $this->databaseName;
    }

    public function generatePdoDsn(string $databaseType): string
    {
        return "{$databaseType}:host={$this->host()};dbname={$this->databaseName()}";
    }
}