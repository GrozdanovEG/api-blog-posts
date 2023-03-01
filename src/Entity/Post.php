<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Entity;

use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use Cocur\Slugify\Slugify;


class Post 
{
    private string $id;
    private string $title;
    private string $slug;
    private string $content;
    private string $author;
    private ?string $thumbnail;
    private ?DateTimeImmutable $postedAt;

    public function __construct(string $id, string $title, string $slug, string $content,
                                string $author, ?string $thumbnail = null, ?DateTimeImmutable $postedAt = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->slug = $slug;
        $this->content = $content;
        $this->author = $author;
        $this->thumbnail = $thumbnail;
        $this->postedAt = $postedAt ?? ( new DateTimeImmutable('now') );
    }

    public static function createFromArrayAssoc(array $array): self
    {
        return new self(
            ($array['id'] ?? $array['id_post'] ?? Uuid::uuid4()->toString()),
            ($array['title'] ?? 'no title provided'),
            ($array['slug'] ?? (new Slugify(['separator' => '-']))->slugify($array['title'])),
            ($array['content'] ?? 'no content provided'),
            ($array['author'] ?? 'no author provided'),
            ($array['thumbnail'] ?? null),
            ($array['posted_at'] ?? (new DateTimeImmutable('now')) )
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

    public function thumbnail(): ?string
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
        return [
            'id' => $this->id(),
            'title' => $this->title(),
            'author' => $this->author(),
            'content' => $this->content(),
            'slug' => $this->slug(),
            'postedAt' => $this->postedAt()->format('M d Y, H:i:s'),
        ];
    }
}