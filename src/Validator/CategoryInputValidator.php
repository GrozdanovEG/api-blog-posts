<?php

declare(strict_types=1);

namespace BlogPostsHandling\Api\Validator;

use BlogPostsHandling\Api\Entity\Category;

class CategoryInputValidator extends InputValidator
{
    public function minimalValidation(): self
    {
        $this->validateId();
        return $this;
    }

    public function defaultValidation(): self
    {
        foreach (['name', 'description'] as $key) {
            if (!isset($this->inputFields[$key]) || $this->inputFields[$key] === '') {
                $this->errorMessages[] = 'Invalid category {' . $key . '} input provided! ';
            }
        }

        return $this;
    }

    public function fullValidation(): self
    {
        foreach (['id', 'name', 'description'] as $key) {
            if (!isset($this->inputFields[$key]) || $this->inputFields[$key] === '') {
                $this->errorMessages[] = 'Invalid category {' . $key . '} input provided! ';
            }
        }

        return $this;
    }

    private function validateId(): void
    {
        if (!isset($this->inputFields['id']) || $this->inputFields['id'] === '') {
            $this->errorMessages[] = 'Invalid category { id } input provided! ';
        }
    }

    /** @param Category $category */
    public function populateWithObjectData(Category $category): self
    {
        foreach (['id', 'name', 'description'] as $key) {
            if (!isset($this->inputFields[$key])) {
                $this->validatedFields[$key] = $category->{$key}();
            } else {
                $this->validatedFields[$key] = $this->inputFields[$key];
            }
        }

        return $this;
    }
}
