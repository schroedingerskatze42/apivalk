<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Tests\PhpUnit\Router;

use PHPUnit\Framework\TestCase;
use apivalk\ApivalkPHP\Router\Router;
use apivalk\ApivalkPHP\Router\Cache\RouterCacheInterface;
use apivalk\ApivalkPHP\Router\Cache\RouterCacheCollection;
use apivalk\ApivalkPHP\Router\Cache\RouterCacheEntry;
use apivalk\ApivalkPHP\Http\Controller\ApivalkControllerFactoryInterface;
use apivalk\ApivalkPHP\Http\Controller\AbstractApivalkController;
use apivalk\ApivalkPHP\Middleware\MiddlewareStack;
use apivalk\ApivalkPHP\Router\Route;
use apivalk\ApivalkPHP\Http\Method\GetMethod;
use apivalk\ApivalkPHP\Http\Response\AbstractApivalkResponse;
use apivalk\ApivalkPHP\Http\Response\NotFoundApivalkResponse;
use apivalk\ApivalkPHP\Http\Response\MethodNotAllowedApivalkResponse;
use apivalk\ApivalkPHP\Http\Request\ApivalkRequestInterface;

class RouterTest extends TestCase
{
    private $serverBackup;

    protected function setUp(): void
    {
        $this->serverBackup = $_SERVER;
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/test';
    }

    protected function tearDown(): void
    {
        $_SERVER = $this->serverBackup;
    }

    public function testDispatchNotFound(): void
    {
        $collection = new RouterCacheCollection();
        $cache = $this->createMock(RouterCacheInterface::class);
        $cache->method('getRouterCacheCollection')->willReturn($collection);
        
        $router = new Router($cache);
        $middleware = $this->createMock(MiddlewareStack::class);
        
        $response = $router->dispatch($middleware);
        $this->assertInstanceOf(NotFoundApivalkResponse::class, $response);
    }

    public function testDispatchMethodNotAllowed(): void
    {
        $route = new Route('/test', new GetMethod());
        $collection = new RouterCacheCollection();
        $collection->addRouteCacheEntry($route, 'TestController');
        
        $cache = $this->createMock(RouterCacheInterface::class);
        $cache->method('getRouterCacheCollection')->willReturn($collection);
        
        $_SERVER['REQUEST_METHOD'] = 'POST';
        
        $router = new Router($cache);
        $middleware = $this->createMock(MiddlewareStack::class);
        
        $response = $router->dispatch($middleware);
        $this->assertInstanceOf(MethodNotAllowedApivalkResponse::class, $response);
    }

    public function testDispatchSuccess(): void
    {
        $route = new Route('/test', new GetMethod());
        
        $request = new class implements ApivalkRequestInterface {
            public static function getDocumentation(): \apivalk\ApivalkPHP\Documentation\ApivalkRequestDocumentation {
                return new \apivalk\ApivalkPHP\Documentation\ApivalkRequestDocumentation();
            }
            public function populate(Route $route): void {}
            public function getMethod(): \apivalk\ApivalkPHP\Http\Method\MethodInterface { return new GetMethod(); }
            public function header(): \apivalk\ApivalkPHP\Http\Request\Parameter\ParameterBag { return new \apivalk\ApivalkPHP\Http\Request\Parameter\ParameterBag(); }
            public function query(): \apivalk\ApivalkPHP\Http\Request\Parameter\ParameterBag { return new \apivalk\ApivalkPHP\Http\Request\Parameter\ParameterBag(); }
            public function body(): \apivalk\ApivalkPHP\Http\Request\Parameter\ParameterBag { return new \apivalk\ApivalkPHP\Http\Request\Parameter\ParameterBag(); }
            public function path(): \apivalk\ApivalkPHP\Http\Request\Parameter\ParameterBag { return new \apivalk\ApivalkPHP\Http\Request\Parameter\ParameterBag(); }
            public function file(): \apivalk\ApivalkPHP\Http\Request\File\FileBag { return new \apivalk\ApivalkPHP\Http\Request\File\FileBag(); }
            public function getAuthIdentity(): ?\apivalk\ApivalkPHP\Security\AbstractAuthIdentity { return null; }
            public function setAuthIdentity(?\apivalk\ApivalkPHP\Security\AbstractAuthIdentity $authIdentity): void {}
        };
        $requestClass = get_class($request);

        $controllerClass = get_class(new class($requestClass) extends AbstractApivalkController {
            private static $req;
            public function __construct($req = null) { if($req) self::$req = $req; }
            public static function getRoute(): Route { return new Route('/test', new GetMethod()); }
            public static function getRequestClass(): string { return self::$req; }
            public static function getResponseClasses(): array { return []; }
            public function __invoke(ApivalkRequestInterface $request): AbstractApivalkResponse {
                return $this->createMock(AbstractApivalkResponse::class);
            }
        });

        $collection = new RouterCacheCollection();
        $collection->addRouteCacheEntry($route, $controllerClass);
        
        $cache = $this->createMock(RouterCacheInterface::class);
        $cache->method('getRouterCacheCollection')->willReturn($collection);
        
        $controller = $this->createMock(AbstractApivalkController::class);
        $factory = $this->createMock(ApivalkControllerFactoryInterface::class);
        $factory->method('create')->with($controllerClass)->willReturn($controller);

        $router = new Router($cache, $factory);
        $middleware = $this->createMock(MiddlewareStack::class);
        $expectedResponse = $this->createMock(AbstractApivalkResponse::class);
        $middleware->method('handle')->willReturn($expectedResponse);
        
        $response = $router->dispatch($middleware);
        $this->assertSame($expectedResponse, $response);
    }
}
