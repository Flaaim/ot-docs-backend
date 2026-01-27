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
    public function build(): File
    {
        $file = new File($this->value);
        $file->mergePaths(new TemplatePath($this->fullPath));

        file_put_contents($file->getFullPath(), 'some_content');

        return $file;
    }
}