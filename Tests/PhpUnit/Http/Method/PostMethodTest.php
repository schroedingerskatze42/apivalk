<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Tests\PhpUnit\Http\Method;

use PHPUnit\Framework\TestCase;
use apivalk\ApivalkPHP\Http\Method\PostMethod;

class PostMethodTest extends TestCase
{
    public function testGetName(): void
    {
        $method = new PostMethod();
        $this->assertEquals('POST', $method->getName());
    }
}
