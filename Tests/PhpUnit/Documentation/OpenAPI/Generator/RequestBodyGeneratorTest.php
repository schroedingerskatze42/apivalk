<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Tests\PhpUnit\Documentation\OpenAPI\Generator;

use PHPUnit\Framework\TestCase;
use apivalk\ApivalkPHP\Documentation\OpenAPI\Generator\RequestBodyGenerator;
use apivalk\ApivalkPHP\Documentation\ApivalkRequestDocumentation;
use apivalk\ApivalkPHP\Router\Route;
use apivalk\ApivalkPHP\Http\Method\GetMethod;

class RequestBodyGeneratorTest extends TestCase
{
    public function testRequestBodyGenerator(): void
    {
        $generator = new RequestBodyGenerator();
        $doc = $this->createMock(ApivalkRequestDocumentation::class);
        $route = $this->createMock(Route::class);
        $route->method('getDescription')->willReturn('Description');
        
        $requestBody = $generator->generate($doc, $route);
        
        $this->assertEquals('Description', $requestBody->getDescription());
    }
}
