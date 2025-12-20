<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Tests\PhpUnit\Http\Controller;

use PHPUnit\Framework\TestCase;
use apivalk\ApivalkPHP\Http\Controller\ApivalkControllerFactoryInterface;

class ApivalkControllerFactoryInterfaceTest extends TestCase
{
    public function testInterfaceExists(): void
    {
        $this->assertTrue(interface_exists(ApivalkControllerFactoryInterface::class));
    }
}
