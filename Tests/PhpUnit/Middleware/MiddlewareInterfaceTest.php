<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Tests\PhpUnit\Middleware;

use PHPUnit\Framework\TestCase;
use apivalk\ApivalkPHP\Middleware\MiddlewareInterface;
use apivalk\ApivalkPHP\Http\Request\ApivalkRequestInterface;
use apivalk\ApivalkPHP\Http\Response\AbstractApivalkResponse;

class MiddlewareInterfaceTest extends TestCase
{
    public function testInterface(): void
    {
        $middleware = new class implements MiddlewareInterface {
            public function process(
                ApivalkRequestInterface $request,
                string $controllerClass,
                callable $next
            ): AbstractApivalkResponse {
                return $next($request);
            }
        };

        $this->assertInstanceOf(MiddlewareInterface::class, $middleware);
    }
}
