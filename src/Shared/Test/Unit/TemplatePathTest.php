<?php

namespace App\Shared\Test\Unit;

use App\Shared\Domain\Service\Template\TemplatePath;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(TemplatePath::class)]
class TemplatePathTest extends TestCase
{
    public function testSuccess(): void
    {
        $file = new TemplatePath(sys_get_temp_dir());
        $this->assertEquals(sys_get_temp_dir(), $file->getValue());
    }
    public function testEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new TemplatePath('');
    }
}
