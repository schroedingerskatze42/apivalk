<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Tests\PhpUnit\Http\Method;

use PHPUnit\Framework\TestCase;
use apivalk\ApivalkPHP\Http\Method\MethodFactory;
use apivalk\ApivalkPHP\Http\Method\GetMethod;
use apivalk\ApivalkPHP\Http\Method\PostMethod;
use apivalk\ApivalkPHP\Http\Method\DeleteMethod;
use apivalk\ApivalkPHP\Http\Method\PatchMethod;
use apivalk\ApivalkPHP\Http\Method\PutMethod;

class MethodFactoryTest extends TestCase
{
    /**
     * @dataProvider methodProvider
     */
    public function testCreate(string $name, string $expectedClass): void
    {
        $method = MethodFactory::create($name);
        $this->assertInstanceOf($expectedClass, $method);
        $this->assertEquals(strtoupper($name), $method->getName());
    }

    public function methodProvider(): array
    {
        return [
            ['GET', GetMethod::class],
            ['get', GetMethod::class],
            ['POST', PostMethod::class],
            ['post', PostMethod::class],
            ['DELETE', DeleteMethod::class],
            ['delete', DeleteMethod::class],
            ['PATCH', PatchMethod::class],
            ['patch', PatchMethod::class],
            ['PUT', PutMethod::class],
            ['put', PutMethod::class],
        ];
    }

    public function testCreateInvalid(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Method "INVALID" is not supported');
        MethodFactory::create('INVALID');
    }
}
