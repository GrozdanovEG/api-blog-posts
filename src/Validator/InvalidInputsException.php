<?php

declare(strict_types=1);

namespace BlogPostsHandling\Api\Validator;

use Throwable;

class InvalidInputsException extends \InvalidArgumentException
{
    /** @var string[]  */
    protected array $errorMessages = [];

    public function __construct(
        array $errorMessages,
        string $message = "",
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->errorMessages = $errorMessages;
    }

    /** @return string[]  */
    public function getErrorMessages(): array
    {
        return $this->errorMessages;
    }
}
