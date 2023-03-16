<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Validator;

class CategoryInputValidator extends InputValidator
{
    public function minimalValidation(): self
    {
        $this->validateId();
        return $this;
    }


    private function validateId(): void
    {
        if ( !isset($this->inputFields['id']) || $this->inputFields['id'] === '')
            $this->errorMessages[] = 'Invalid category { id } input provided! ';
    }
}