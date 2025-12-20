<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Tests\PhpUnit\Http\Request;

use PHPUnit\Framework\TestCase;
use apivalk\ApivalkPHP\Http\Request\AbstractApivalkRequest;
use apivalk\ApivalkPHP\Documentation\ApivalkRequestDocumentation;
use apivalk\ApivalkPHP\Http\Method\MethodInterface;
use apivalk\ApivalkPHP\Router\Route;
use apivalk\ApivalkPHP\Security\AbstractAuthIdentity;

class AbstractApivalkRequestTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $request = new class extends AbstractApivalkRequest {
            public static function getDocumentation(): ApivalkRequestDocumentation
            {
                return new ApivalkRequestDocumentation();
            }
        };

        $this->assertNull($request->getAuthIdentity());
        $auth = $this->createMock(AbstractAuthIdentity::class);
        $request->setAuthIdentity($auth);
        $this->assertSame($auth, $request->getAuthIdentity());
    }

    public function testPopulate(): void
    {
        $request = new class extends AbstractApivalkRequest {
            public static function getDocumentation(): ApivalkRequestDocumentation
            {
                return new ApivalkRequestDocumentation();
            }
        };

        $method = $this->createMock(MethodInterface::class);
        $route = $this->createMock(Route::class);
        $route->method('getMethod')->willReturn($method);
        
        // Mock global factories is hard, but we can check if they are called and set bags
        $request->populate($route);

        $this->assertSame($method, $request->getMethod());
        $this->assertInstanceOf(\apivalk\ApivalkPHP\Http\Request\Parameter\ParameterBag::class, $request->header());
        $this->assertInstanceOf(\apivalk\ApivalkPHP\Http\Request\Parameter\ParameterBag::class, $request->query());
        $this->assertInstanceOf(\apivalk\ApivalkPHP\Http\Request\Parameter\ParameterBag::class, $request->body());
        $this->assertInstanceOf(\apivalk\ApivalkPHP\Http\Request\Parameter\ParameterBag::class, $request->path());
        $this->assertInstanceOf(\apivalk\ApivalkPHP\Http\Request\File\FileBag::class, $request->file());
    }
}
