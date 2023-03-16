<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Validator;

use BlogPostsHandling\Api\Entity\Post;

class PostInputValidator extends InputValidator
{

    public function minimalValidation(): self
    {
        $this->validateId();
        return $this;
    }

    public function defaultValidation(): self
    {
        foreach (['title', 'content', 'thumbnail', 'author'] as $key) {
            if ( !isset($this->inputFields[$key]) || $this->inputFields[$key] === '') {
                $this->errorMessages[] = 'Invalid {' . $key . '} input provided! ';
            }
        }
        return $this;
    }

    public function fullValidation(): self
    {
        foreach (['id', 'title', 'slug', 'content', 'thumbnail', 'author'] as $key) {
            if ( !isset($this->inputFields[$key]) || $this->inputFields[$key] === '') {
                $this->errorMessages[] = 'Invalid {' . $key . '} input provided! ';
            }
        }
        $postedAtInput = $this->inputFields['posted_at'] ?? $this->inputFields['postedAt'] ?? null;
        if ($postedAtInput === null) {
            $this->errorMessages[] = 'Invalid { postedAt } input provided! ';
        }
        return $this;
    }

    public function slugValidation(): self
    {
        if ( !isset($this->inputFields['slug']) || $this->inputFields['slug'] === '')
            $this->errorMessages[] = 'Invalid { slug } input provided! ';

        return $this;
    }

    private function validateId(): void
    {
        if ( !isset($this->inputFields['id']) || $this->inputFields['id'] === '')
            $this->errorMessages[] = 'Invalid { id } input provided! ';
    }

    /** @var Post $post */
    public function populateWithObjectData(Post $post): self
    {
        foreach (['id', 'title', 'slug', 'content', 'thumbnail', 'author', 'postedAt'] as $key)
        if (!isset($this->inputFields[$key]) ) $this->inputFields[$key] = $post->{$key}();

        $postedAtInput = $this->inputFields['posted_at'] ?? $this->inputFields['postedAt'] ?? null;
        if ($postedAtInput === null) $this->inputFields['postedAt'] = $post->postedAt();

        return $this;
    }

    /** @throws InvalidInputsException */
    public function sendResult(): bool
    {
        if (count($this->errorMessages) > 0)
            throw new InvalidInputsException($this->errorMessages);

        return true;
    }
}