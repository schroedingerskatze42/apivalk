<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Tests\PhpUnit\Router;

use PHPUnit\Framework\TestCase;
use apivalk\ApivalkPHP\Router\AbstractRouter;
use apivalk\ApivalkPHP\Router\Cache\RouterCacheInterface;
use apivalk\ApivalkPHP\Http\Controller\ApivalkControllerFactoryInterface;
use apivalk\ApivalkPHP\Middleware\MiddlewareStack;
use apivalk\ApivalkPHP\Http\Response\AbstractApivalkResponse;

class AbstractRouterTest extends TestCase
{
    public function testGetters(): void
    {
        $cache = $this->createMock(RouterCacheInterface::class);
        $factory = $this->createMock(ApivalkControllerFactoryInterface::class);
        
        $router = new class($cache, $factory) extends AbstractRouter {
            public function dispatch(MiddlewareStack $middlewareStack): AbstractApivalkResponse {
                return $this->createMock(AbstractApivalkResponse::class);
            }
        };
        
        $this->assertSame($cache, $router->getRouterCache());
        $this->assertSame($factory, $router->getControllerFactory());
    }

    public function testDefaultFactory(): void
    {
        $cache = $this->createMock(RouterCacheInterface::class);
        $router = new class($cache) extends AbstractRouter {
            public function dispatch(MiddlewareStack $middlewareStack): AbstractApivalkResponse {
                return $this->createMock(AbstractApivalkResponse::class);
            }
        };
        
        $this->assertInstanceOf(ApivalkControllerFactoryInterface::class, $router->getControllerFactory());
    }
}
