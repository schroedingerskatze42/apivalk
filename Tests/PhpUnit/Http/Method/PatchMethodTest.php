<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Tests\PhpUnit\Http\Method;

use PHPUnit\Framework\TestCase;
use apivalk\ApivalkPHP\Http\Method\PatchMethod;

class PatchMethodTest extends TestCase
{
    public function testGetName(): void
    {
        $method = new PatchMethod();
        $this->assertEquals('PATCH', $method->getName());
    }
}
