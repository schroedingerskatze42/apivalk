<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Tests\PhpUnit\Documentation\OpenAPI\Generator;

use PHPUnit\Framework\TestCase;
use apivalk\ApivalkPHP\Documentation\OpenAPI\Generator\PathItemGenerator;
use apivalk\ApivalkPHP\Router\Route;
use apivalk\ApivalkPHP\Http\Method\GetMethod;
use apivalk\ApivalkPHP\Http\Controller\AbstractApivalkController;
use apivalk\ApivalkPHP\Http\Request\ApivalkRequestInterface;
use apivalk\ApivalkPHP\Documentation\ApivalkRequestDocumentation;

class PathItemTestController extends AbstractApivalkController {
    public static function getRoute(): Route { return new Route('/test', new GetMethod()); }
    public static function getRequestClass(): string { return PathItemTestRequest::class; }
    public static function getResponseClasses(): array { return []; }
    public function __invoke(ApivalkRequestInterface $request): \apivalk\ApivalkPHP\Http\Response\AbstractApivalkResponse { 
        return new class extends \apivalk\ApivalkPHP\Http\Response\AbstractApivalkResponse {
            public static function getDocumentation(): \apivalk\ApivalkPHP\Documentation\ApivalkResponseDocumentation { return new \apivalk\ApivalkPHP\Documentation\ApivalkResponseDocumentation(); }
            public static function getStatusCode(): int { return 200; }
            public function toArray(): array { return []; }
        };
    }
}

class PathItemTestRequest implements ApivalkRequestInterface {
    public static function getDocumentation(): ApivalkRequestDocumentation { return new ApivalkRequestDocumentation(); }
    public function populate(Route $route): void {}
    public function getMethod(): \apivalk\ApivalkPHP\Http\Method\MethodInterface { return new GetMethod(); }
    public function header(): \apivalk\ApivalkPHP\Http\Request\Parameter\ParameterBag { return \apivalk\ApivalkPHP\Http\Request\Parameter\ParameterBagFactory::createHeaderBag(); }
    public function query(): \apivalk\ApivalkPHP\Http\Request\Parameter\ParameterBag { return \apivalk\ApivalkPHP\Http\Request\Parameter\ParameterBagFactory::createQueryBag(self::getDocumentation()); }
    public function body(): \apivalk\ApivalkPHP\Http\Request\Parameter\ParameterBag { return \apivalk\ApivalkPHP\Http\Request\Parameter\ParameterBagFactory::createBodyBag(self::getDocumentation()); }
    public function path(): \apivalk\ApivalkPHP\Http\Request\Parameter\ParameterBag { return \apivalk\ApivalkPHP\Http\Request\Parameter\ParameterBagFactory::createPathBag(new Route('', new GetMethod()), self::getDocumentation()); }
    public function file(): \apivalk\ApivalkPHP\Http\Request\File\FileBag { return \apivalk\ApivalkPHP\Http\Request\File\FileBagFactory::create(); }
    public function getAuthIdentity(): ?\apivalk\ApivalkPHP\Security\AbstractAuthIdentity { return null; }
    public function setAuthIdentity(?\apivalk\ApivalkPHP\Security\AbstractAuthIdentity $authIdentity): void {}
}

class PathItemGeneratorTest extends TestCase
{
    public function testPathItemGenerator(): void
    {
        $generator = new PathItemGenerator();
        
        $method = $this->createMock(GetMethod::class);
        $method->method('getName')->willReturn('GET');

        $route = $this->createMock(Route::class);
        $route->method('getMethod')->willReturn($method);
        $route->method('getUrl')->willReturn('/test');
        $route->method('getDescription')->willReturn('desc');
        $route->method('getTags')->willReturn([]);
        $route->method('getSecurityRequirements')->willReturn([]);

        $routes = [
            ['route' => $route, 'controllerClass' => PathItemTestController::class]
        ];
        
        $pathItem = $generator->generate($routes);
        $this->assertNotNull($pathItem->getGet());
    }
}
