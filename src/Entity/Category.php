<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Entity;

use http\Exception\BadQueryStringException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Category
{
    private UuidInterface $id;
    private string $name;
    private string $description;

    public function __construct(string $name, string $description)
    {
        $this->id = Uuid::uuid4();
        $this->name = $name;
        $this->description = $description;

    }

    public function id(): UuidInterface
    { 
        return $this->id;
    }

    public function name(): string
    { 
        return $this->name;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function __toString(): string
    {
        return '['.$this->id->toString().': ['.$this->name().': '.$this->description().']]';
    }
}