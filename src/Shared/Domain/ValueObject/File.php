<?php

namespace App\Shared\Domain\ValueObject;

use App\Shared\Domain\Service\Template\TemplatePath;
use Webmozart\Assert\Assert;

class File
{
    private string $value;
    private ?string $fullPath = null;
    public function __construct(string $pathToFile)
    {
        Assert::notEmpty($pathToFile);
        $this->value = trim($pathToFile, '/');
    }
    public function mergePaths(TemplatePath $templatePath): void
    {
        Assert::notEmpty($templatePath->getValue());
        $this->fullPath = DIRECTORY_SEPARATOR . trim($templatePath->getValue(), '/') . DIRECTORY_SEPARATOR . $this->value;
    }
    public function getValue(): string
    {
        return $this->value;
    }
    public function getFullPath(): ?string
    {
        return $this->fullPath;
    }
    public function getFile(): string
    {
        if(null === $this->fullPath) {
            throw new \DomainException('File path not set.');
        }
        if(!$this->exists()){
            throw new \DomainException("File '{$this->fullPath}' does not exist");
        }
        return $this->fullPath;
    }
    public function exists(): bool
    {
        return file_exists($this->fullPath);
    }
}
