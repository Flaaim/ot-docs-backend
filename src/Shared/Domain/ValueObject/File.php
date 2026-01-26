<?php

namespace App\Shared\Domain\ValueObject;

use App\Shared\Domain\Service\Template\TemplatePath;
use Webmozart\Assert\Assert;

class File
{
    private string $value;
    public function __construct(string $pathToFile, TemplatePath $templatePath)
    {
        Assert::notEmpty($pathToFile);
        $this->value =
            DIRECTORY_SEPARATOR .
            trim($templatePath->getValue(), '/') .
            DIRECTORY_SEPARATOR .
            trim($pathToFile, '/');
    }
    public function getPath(): string
    {
        return $this->value;
    }
    public function getFile(): string
    {
        if(!$this->exists()){
            throw new \DomainException("File '{$this->value}' does not exist");
        }
        return $this->value;
    }
    public function exists(): bool
    {
        return file_exists($this->value);
    }
}
