<?php

namespace App\Shared\Test\Unit\ValueObject;

use App\Shared\Domain\Service\Template\TemplatePath;
use App\Shared\Domain\ValueObject\File;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{
    public function testSuccess(): void
    {
        $fileName = $this->createFile();
        $file = new File($fileName, new TemplatePath(sys_get_temp_dir()));

        $this->assertEquals('/tmp/file.txt', $file->getPath());
        $this->assertTrue($file->exists());

        $this->clearTempDir();
    }

    #[DataProvider('fileNameProvider')]
    public function testTrimSlash($file, $root, $expected): void
    {
        $file = new File($file, new TemplatePath($root));
        $this->assertEquals($expected, $file->getPath());
    }

    public static function fileNameProvider(): array
    {
        return [
          ['file.txt', '/tmp/', '/tmp/file.txt'],
          ['/file.txt', '/tmp//', '/tmp/file.txt'],
          ['/file.txt/', '/tmp//', '/tmp/file.txt'],
          ['//file.txt/', 'tmp', '/tmp/file.txt'],
        ];
    }
    public function testEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new File('', new TemplatePath(sys_get_temp_dir()));
    }

    private function createFile(): string
    {
        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'file.txt';
        file_put_contents($path, 'some_content');
        return basename($path);
    }
    private function clearTempDir(): void
    {
        unlink(sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'file.txt');
    }

}
