<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Storage;

class DatabaseDataObject
{
    private string $databaseType;
    private string $host;
    private string $username;
    private string $password;
    private string $databaseName;
    private string $port;

    public function __construct(array $dbData)
    {
        $this->databaseType = $dbData['databaseType'];
        $this->host = $dbData['host'];
        $this->username = $dbData['username'];
        $this->password = $dbData['password'];
        $this->databaseName = $dbData['databaseName'];
        $this->port = $dbData['port'];
    }

    public function databaseType(): string
    {
        return $this->databaseType;
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

    public function port(): string
    {
        return $this->port;
    }

    /** @return string */
    public function generatePdoDsn(?string $databaseType = null): string
    {
        $dbType = $databaseType ?? $this->databaseType();
        return "{$dbType}:host={$this->host()};port={$this->port()};dbname={$this->databaseName()}";
    }

    public function __toString(): string
    {
        return $this->generatePdoDsn();
    }
}