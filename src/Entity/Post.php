<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Entity;

use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Post 
{
    private string $id;
    private string $title;
    private string $slug;
    private string $content;
    private string $thumbnail;
    private string $author;
    private ?DateTimeImmutable $postedAt;

    public function __construct(string $id, string $title, string $slug, string $content,
                                string $thumbnail, string $author, ?DateTimeImmutable $postedAt = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->slug = $slug;
        $this->content = $content;
        $this->thumbnail = $thumbnail;
        $this->author = $author;
        $this->postedAt = $postedAt ?? ( new DateTimeImmutable('now') );
    }

    public static function createFromArrayAssoc(array $array): self
    {
        return new self(
            ($array['id'] ?? $array['id_post'] ?? Uuid::uuid4()->toString()),
            ($array['title'] ?? 'no title provided'),
            ($array['slug'] ?? 'no slug provided'),
            ($array['content'] ?? 'no content provided'),
            ($array['thumbnail'] ?? 'no thumbnail provided'),
            ($array['posted_at'] ?? 'no posted at provided')
        );
    }

    public function id(): string
    { 
        return $this->id;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function slug(): string
    {
        return $this->slug;
    }

    public function content(): string
    {
        return $this->content;
    }

    public function thumbnail(): string
    {
        return $this->thumbnail;
    }

    public function author(): string
    { 
        return $this->author;
    }

    public function postedAt(): DateTimeImmutable
    {
        return $this->postedAt;
    }

    public function toMap(): array
    {
        /** @todo to be implemented */
        return [];
    }
}