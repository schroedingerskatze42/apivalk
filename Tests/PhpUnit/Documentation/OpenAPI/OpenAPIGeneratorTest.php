<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Tests\PhpUnit\Documentation\OpenAPI;

use PHPUnit\Framework\TestCase;
use apivalk\ApivalkPHP\Documentation\OpenAPI\OpenAPIGenerator;
use apivalk\ApivalkPHP\Apivalk;
use apivalk\ApivalkPHP\Router\AbstractRouter;
use apivalk\ApivalkPHP\Router\Cache\RouterCacheInterface;
use apivalk\ApivalkPHP\Router\Cache\RouterCacheCollection;

class OpenAPIGeneratorTest extends TestCase
{
    public function testGenerate(): void
    {
        $apivalk = $this->createMock(Apivalk::class);
        $router = $this->createMock(AbstractRouter::class);
        $routerCache = $this->createMock(RouterCacheInterface::class);
        $routerCacheCollection = new RouterCacheCollection();

        $apivalk->method('getRouter')->willReturn($router);
        $router->method('getRouterCache')->willReturn($routerCache);
        $routerCache->method('getRouterCacheCollection')->willReturn($routerCacheCollection);

        $generator = new OpenAPIGenerator($apivalk);
        $json = $generator->generate();

        $this->assertJson($json);
        $this->assertStringContainsString('"openapi":"3.1.1"', $json);
    }

    public function testGenerateInvalidFormat(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $apivalk = $this->createMock(Apivalk::class);
        $generator = new OpenAPIGenerator($apivalk);
        $generator->generate('xml');
    }
}
