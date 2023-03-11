<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Entity;

use Ramsey\Uuid\Uuid;

class FileUploaded
{
    private string $hostFilename;
    private string $hostUploadFolderPath;
    private string $hostRootUri;
    private string $customFilename;
    private string $b64FileContent;

    public function __construct(string $b64SourceString, ?string $customFilename = '')
    {
        if ( $this->extractPropertiesFromB64String($b64SourceString) ) {
            if ($customFilename !== '') $this->customFilename = $customFilename;
            else $this->customFilename = $this->hostFilename;
        }
        $this->extractPropertiesFromEnvironment();
    }

    private function extractPropertiesFromB64String(string $b64SourceString): bool
    {
        try{
            $b64Parts = explode(',', $b64SourceString);
            $this->b64FileContent = $b64Parts[count($b64Parts)-1];
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

    public function extractPropertiesFromEnvironment(): bool
    {
        try {
            $this->hostUploadFolderPath = $_ENV['HOST_THUMBNAILS_PATH'];
            $this->hostRootUri = $_ENV['HOST_ROOT_URI'];
            return true;
        } catch (\Throwable $th) {
            error_log($th->getFile() . ':' . $th->getLine() . PHP_EOL . $th->getMessage());
        }
        return false;
    }

    public function hostFilename(): string
    {
        return $this->hostFilename;
    }

    public function hostUploadFolderPath(): string
    {
        return $this->hostUploadFolderPath;
    }

    public function b64FileContent(): string
    {
        return $this->b64FileContent;
    }

    public function customFilename(): string
    {
        return $this->customFilename;
    }

    public function getFullFilePath(): string
    {
        return $this->hostUploadFolderPath. '/' .$this->hostFilename;
    }

    public function getFullUri(): string
    {
        return $this->hostRootUri . '/' . $this->getFullFilePath();
    }

    public function store(?string $rootPath = ''): bool
    {
        try {
            if ($rootPath === '') $rootPath =  __DIR__;
            file_put_contents( $rootPath . $this->getFullFilePath(),
                                base64_decode( $this->b64FileContent() )
            );
            return true;
        } catch(\Throwable $th) {
            error_log($th->getFile() . ':' . $th->getLine() . PHP_EOL . $th->getMessage());
            return false;
        }
    }

    public function toMapShort(): array
    {
        return ['filename' => $this->hostFilename, 'uri' => $this->getFullUri];
    }

    public function __toString(): string
    {
        return $this->hostFilename;
    }
}