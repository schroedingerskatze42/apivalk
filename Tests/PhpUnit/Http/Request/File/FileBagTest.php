<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Tests\PhpUnit\Http\Request\File;

use PHPUnit\Framework\TestCase;
use apivalk\ApivalkPHP\Http\Request\File\FileBag;
use apivalk\ApivalkPHP\Http\Request\File\File;

class FileBagTest extends TestCase
{
    public function testBag(): void
    {
        $bag = new FileBag();
        $file = $this->createMock(File::class);
        $file->method('getName')->willReturn('avatar');

        $bag->set($file);

        $this->assertTrue($bag->has('avatar'));
        $this->assertFalse($bag->has('other'));
        $this->assertSame($file, $bag->get('avatar'));
        $this->assertNull($bag->get('other'));
        $this->assertCount(1, $bag);
        
        foreach ($bag as $name => $f) {
            $this->assertEquals('avatar', $name);
            $this->assertSame($file, $f);
        }
    }
}
