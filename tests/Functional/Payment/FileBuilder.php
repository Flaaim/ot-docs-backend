<?php

namespace Test\Functional\Payment;

use App\Shared\Domain\Service\Template\TemplatePath;
use App\Shared\Domain\ValueObject\File;

class FileBuilder
{
    private string $value;
    private string $fullPath;

    public function __construct()
    {
        $this->value = 'file.txt';
        $this->fullPath = sys_get_temp_dir();
    }
    public function withValue(string $value): self
    {
        $this->value = $value;
        return $this;
    }
    public function withFullPath(string $fullPath): self
    {
        $this->fullPath = $fullPath;
        return $this;
    }
    public function writeDownFile(): self
    {
        $pathToDir = dirname($this->fullPath. DIRECTORY_SEPARATOR . $this->value);
        if(!is_dir($pathToDir)) {
            mkdir($pathToDir);
        }
        file_put_contents($this->fullPath. DIRECTORY_SEPARATOR . $this->value, 'some_content');
        return $this;
    }
    public function build(): File
    {
        $file = new File($this->value);

        $file->mergePaths(new TemplatePath($this->fullPath));

        $this->writeDownFile();

        return $file;
    }
}