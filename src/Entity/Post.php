<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Entity;

use DateTime;

class Post 
{
  private string $id;
  private string $title;
  private string $slug;
  private string $content;
  private string $thumbnail;
  private string $author;
  private DateTime $postedAt;

  public function __construct()
  {
      $this->postedAt = new DateTime('now');
  }

  public function id(): string
    { 
        return $this->id;
    }

    public function title(): string
    { 
        return $this->title;
    }

    public function content(): string
    { 
        return $this->content;
    }

    public function author(): string
    { 
        return $this->author;
    }

    public function postedAd(): DateTime
    { 
        return $this->postedAt;
    }
}