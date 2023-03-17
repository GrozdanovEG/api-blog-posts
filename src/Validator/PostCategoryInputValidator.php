<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Validator;

class PostCategoryInputValidator extends InputValidator
{
    public function minimalValidation(): self
    {
        $this->validateIds();
        return $this;
    }

    private function validateIds(): void
    {
        if ( !isset($this->inputFields['pid']) || $this->inputFields['pid'] === '')
            $this->errorMessages[] = 'Invalid post { id } input provided! ';

        if ( !isset($this->inputFields['cid']) || $this->inputFields['cid'] === '')
            $this->errorMessages[] = 'Invalid category { id } input provided! ';
    }
}