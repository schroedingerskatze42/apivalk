<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Tests\PhpUnit\Http\Controller;

use PHPUnit\Framework\TestCase;
use apivalk\ApivalkPHP\Http\Controller\ApivalkControllerFactory;
use apivalk\ApivalkPHP\Http\Controller\AbstractApivalkController;
use Psr\Container\ContainerInterface;

class ApivalkControllerFactoryTest extends TestCase
{
    public function testCreateWithContainer(): void
    {
        $controller = $this->createMock(AbstractApivalkController::class);
        $container = $this->createMock(ContainerInterface::class);
        $container->method('has')->with('MyController')->willReturn(true);
        $container->method('get')->with('MyController')->willReturn($controller);

        $factory = new ApivalkControllerFactory($container);
        $result = $factory->create('MyController');

        $this->assertSame($controller, $result);
    }

    public function testCreateWithoutContainer(): void
    {
        $factory = new ApivalkControllerFactory();
        
        // Use an anonymous class that exists
        $controllerClass = get_class(new class extends AbstractApivalkController {
            public static function getRoute(): \apivalk\ApivalkPHP\Router\Route { return new \apivalk\ApivalkPHP\Router\Route('/', new \apivalk\ApivalkPHP\Http\Method\GetMethod()); }
            public static function getRequestClass(): string { return ''; }
            public static function getResponseClasses(): array { return []; }
            public function __invoke(\apivalk\ApivalkPHP\Http\Request\ApivalkRequestInterface $request): \apivalk\ApivalkPHP\Http\Response\AbstractApivalkResponse { return $this->createMock(\apivalk\ApivalkPHP\Http\Response\AbstractApivalkResponse::class); }
        });

        $result = $factory->create($controllerClass);
        $this->assertInstanceOf($controllerClass, $result);
    }

    public function testCreateNonExistentClass(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Controller class "NonExistent" does not exist');
        
        $factory = new ApivalkControllerFactory();
        $factory->create('NonExistent');
    }

    public function testCreateInvalidClass(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('must extend AbstractApivalkController');
        
        $factory = new ApivalkControllerFactory();
        $factory->create(\stdClass::class);
    }
}
