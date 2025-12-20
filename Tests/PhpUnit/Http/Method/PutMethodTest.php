<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Tests\PhpUnit\Http\Method;

use PHPUnit\Framework\TestCase;
use apivalk\ApivalkPHP\Http\Method\PutMethod;

class PutMethodTest extends TestCase
{
    public function testGetName(): void
    {
        $method = new PutMethod();
        $this->assertEquals('PUT', $method->getName());
    }
}
