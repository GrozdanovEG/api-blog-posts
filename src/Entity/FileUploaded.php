<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Entity;

use Ramsey\Uuid\Uuid;

class FileUploaded
{
    private string $hostFilename;
    private string $hostUploadUri;
    private string $customFilename;
    private string $b64FileContent;

    public function __construct(string $b64SourceString,
                                ?string $customFilename = '')
    {
        if ( $this->extractPropertiesFromB64String($b64SourceString) ) {
            if ($customFilename !== '') $this->customFilename = $customFilename;
            else $this->customFilename = $this->hostFilename;
        }
    }

    private function extractPropertiesFromB64String(string $b64SourceString): bool
    {
        try{
            $b64Parts = explode(',', $b64SourceString);
            $this->b64FileContent = $b64Parts[count($b64Parts)-1];
            // data:image/jpeg;base64
            $fileExtension = explode(';',
                    explode('/', $b64Parts[0])[1]
                )[0];
            $this->hostFilename = Uuid::uuid4()->toString() . '.' . $fileExtension;
            return true;
        } catch (\Throwable $th) {
            error_log($th->getFile() . ':' . $th->getLine() . PHP_EOL . $th->getMessage());
            return false;
        }
    }

    public function extractPropertiesFromEnvironment(string $envKeyName): bool
    {
        try {
            $this->hostUploadUri = $_ENV[$envKeyName];
            return true;
        } catch (\Throwable $th) {
            error_log($th->getFile() . ':' . $th->getLine() . PHP_EOL . $th->getMessage());
        }
        return false;
    }

    public function hostFilename(): string
    {
        return $this->hostFilename();
    }

    public function hostUploadFolder(): string
    {
        return $this->hostUploadFolder;
    }

    public function b64FileContent(): string
    {
        return $this->b64FileContent;
    }

    public function customFilename(): string
    {
        return $this->customFilename;
    }

    public function getFullUri(): string
    {
        return $this->hostUploadFolder() . '/' .$this->hostFilename();
    }

    public function __toString(): string
    {
        return '[File: ' . $this->customFilename() .' at: ' . $this->getFullUri() . ']' . PHP_EOL;
    }
}