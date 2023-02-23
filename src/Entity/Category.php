<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Entity;

use Ramsey\Uuid\Uuid;

class Category
{
    private string $id;
    private string $name;
    private string $description;

    public function __construct(string $id, string $name, string $description)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;

    }

    public static function createFromArrayAssoc(array $array): self
    {
        return new self(
            ($array['id'] ?? $array['id_category'] ?? Uuid::uuid4()->toString()),
            ($array['name'] ?? 'no name provided'),
            ($array['description'] ?? 'no description provided')
        );
    }


    public function id(): string
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
        return '['.$this->id().': ['.$this->name().': '.$this->description().']]';
    }
}