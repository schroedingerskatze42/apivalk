<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Tests\PhpUnit\Http\Renderer;

use PHPUnit\Framework\TestCase;
use apivalk\ApivalkPHP\Http\Renderer\RendererInterface;

class RendererInterfaceTest extends TestCase
{
    public function testInterfaceExists(): void
    {
        $this->assertTrue(interface_exists(RendererInterface::class));
    }
}
