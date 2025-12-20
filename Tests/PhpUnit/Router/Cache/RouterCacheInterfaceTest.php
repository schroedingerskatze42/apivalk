<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Tests\PhpUnit\Router\Cache;

use PHPUnit\Framework\TestCase;
use apivalk\ApivalkPHP\Router\Cache\RouterCacheInterface;
use apivalk\ApivalkPHP\Router\Cache\RouterCacheCollection;

class RouterCacheInterfaceTest extends TestCase
{
    public function testInterface(): void
    {
        $cache = $this->createMock(RouterCacheInterface::class);
        $collection = $this->createMock(RouterCacheCollection::class);
        
        $cache->method('getRouterCacheCollection')->willReturn($collection);
        
        $this->assertSame($collection, $cache->getRouterCacheCollection());
    }
}
