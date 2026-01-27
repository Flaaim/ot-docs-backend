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
        $file = new File($name = 'file.txt');

        self::assertEquals($name,  $file->getValue());
        self::assertNull($file->getFullPath());
    }
    public function testEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new File('');
    }
    public function testTrimName(): void
    {
        $name = '/file.txt';
        $file = new File($name);
        $this->assertEquals('file.txt', $file->getValue());
    }
    public function testMergePaths(): void
    {
        $file = new File('file.txt');
        $root = new TemplatePath(sys_get_temp_dir());
        $file->mergePaths($root);
        self::assertEquals('/tmp/file.txt', $file->getFullPath());
    }
    #[DataProvider('fileNameProvider')]
    public function testPaths($name, $root, $expected): void
    {
        $file = new File($name);
        $file->mergePaths(new TemplatePath($root));

        $this->assertEquals($expected, $file->getFullPath());
    }

    public static function fileNameProvider(): array
    {
        return [
          ['file.txt', '/tmp/', '/tmp/file.txt'],
          ['/file.txt', '/tmp//', '/tmp/file.txt'],
          ['/file.txt/', '/tmp//', '/tmp/file.txt'],
          ['//file.txt/', 'tmp', '/tmp/file.txt'],
          ['/safety/file.txt/', '/tmp', '/tmp/safety/file.txt'],
        ];
    }

    public function testFileExists(): void
    {
        $name = $this->createFile('file.txt');
        $file = new File($name);
        $file->mergePaths(new TemplatePath(sys_get_temp_dir()));
        self::assertTrue($file->exists());

        $this->clearTempDir();
    }
    public function testGetFile(): void
    {
        $name = $this->createFile('file.txt');
        $file = new File($name);
        $file->mergePaths(new TemplatePath(sys_get_temp_dir()));

        self::assertEquals('/tmp/file.txt', $file->getFile());
        $this->clearTempDir();
    }
    public function testNotExists(): void
    {
        $file = new File('file.txt');

        self::assertFalse($file->exists());

        self::expectException(\DomainException::class);
        self::expectExceptionMessage('File path not set.');
        $file->getFile();
    }
    private function createFile(string $name): string
    {
        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $name;
        file_put_contents($path, 'some_content');
        return basename($path);
    }
    private function clearTempDir(): void
    {
        unlink(sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'file.txt');
    }

}
