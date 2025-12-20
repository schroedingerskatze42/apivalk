<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Tests\PhpUnit\Http\Method;

use PHPUnit\Framework\TestCase;
use apivalk\ApivalkPHP\Http\Method\DeleteMethod;

class DeleteMethodTest extends TestCase
{
    public function testGetName(): void
    {
        $method = new DeleteMethod();
        $this->assertEquals('DELETE', $method->getName());
    }
}
