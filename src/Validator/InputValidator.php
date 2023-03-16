<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Validator;

class InputValidator
{
    /** @var string[]  */
    protected array $errorMessages = [];

    /** @var string[]  */
    protected array $inputFields;

    public function __construct(array $inputFields)
    {
        $this->inputFields = $inputFields;
    }

    /** @throws InvalidInputsException */
    public function sendResult(): bool
    {
        if (count($this->errorMessages) > 0)
            throw new InvalidInputsException($this->errorMessages);

        return true;
    }

}