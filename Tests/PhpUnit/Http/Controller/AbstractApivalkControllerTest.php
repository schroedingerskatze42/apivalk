<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Tests\PhpUnit\Http\Controller;

use PHPUnit\Framework\TestCase;
use apivalk\ApivalkPHP\Http\Controller\AbstractApivalkController;
use apivalk\ApivalkPHP\Http\Request\ApivalkRequestInterface;
use apivalk\ApivalkPHP\Http\Response\AbstractApivalkResponse;
use apivalk\ApivalkPHP\Router\Route;

class AbstractApivalkControllerTest extends TestCase
{
    public function testController(): void
    {
        $controller = new class extends AbstractApivalkController {
            public static function getRoute(): Route { return new Route('/', new \apivalk\ApivalkPHP\Http\Method\GetMethod()); }
            public static function getRequestClass(): string { return 'RequestClass'; }
            public static function getResponseClasses(): array { return ['ResponseClass']; }
            public function __invoke(ApivalkRequestInterface $request): AbstractApivalkResponse 
            {
                 return new class extends AbstractApivalkResponse {
                     public static function getDocumentation(): \apivalk\ApivalkPHP\Documentation\ApivalkResponseDocumentation { return new \apivalk\ApivalkPHP\Documentation\ApivalkResponseDocumentation(); }
                     public static function getStatusCode(): int { return 200; }
                     public function toArray(): array { return []; }
                 };
            }
        };

        $this->assertInstanceOf(Route::class, $controller::getRoute());
        $this->assertEquals('RequestClass', $controller::getRequestClass());
        $this->assertEquals(['ResponseClass'], $controller::getResponseClasses());
        
        $request = $this->createMock(ApivalkRequestInterface::class);
        $this->assertInstanceOf(AbstractApivalkResponse::class, $controller($request));
    }
}
