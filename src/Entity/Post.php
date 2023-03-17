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
    private ?FileUploaded $thumbnail;
    private ?DateTimeImmutable $postedAt;
    private array $categories = [];

    public function __construct(string $id, string $title, string $slug, string $content,
                                string $author, ?FileUploaded $thumbnail = null, ?DateTimeImmutable $postedAt = null)
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
        $postedAtString = $array['posted_at'] ?? $array['postedAt'] ?? null;
        $postedAt = ($postedAtString ) ?
            (DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $postedAtString)) :
            (new DateTimeImmutable('now'));
        return new self(
            ($array['id'] ?? $array['id_post'] ?? Uuid::uuid4()->toString()),
            ($array['title'] ?? 'no title provided'),
            ($array['slug'] ?? (new Slugify(['separator' => '-']))->slugify($array['title'])),
            ($array['content'] ?? 'no content provided'),
            ($array['author'] ?? 'no author provided'),
            ($array['thumbnail'] ?? null),
            ($postedAt)
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

    public function thumbnail(): ?FileUploaded
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

    /** @var Category $category
     *  @return Post  */
    public function addCategory(Category $category): self
    {
        $this->categories[] = $category;
        return $this;
    }

    /** @return Category[] */
    public function categories(): array
    {
        return $this->categories;
    }

    public function toMap(): array
    {
        $tbnl = $this->thumbnail();

        return [
            'id' => $this->id(),
            'title' => $this->title(),
            'slug' => $this->slug(),
            'content' => $this->content(),
            'thumbnail' => ( ($tbnl instanceof FileUploaded) ? $tbnl->toMapShort() : '' ),
            'author' => $this->author(),
            'postedAt' => $this->postedAt()->format('Y-m-d H:i:s'),
            'categories' => array_map(
                function($c) { return $c->toMapShort();},
                $this->categories()
            )
        ];
    }

    public function toMapShort(): array
    {
        return [
            'id' => $this->id(),
            'title' => $this->title(),
            'author' => $this->author(),
            'postedAt' => $this->postedAt()->format('Y-m-d H:i:s')
            ];
    }
}