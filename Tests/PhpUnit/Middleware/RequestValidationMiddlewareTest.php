<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Tests\PhpUnit\Middleware;

use PHPUnit\Framework\TestCase;
use apivalk\ApivalkPHP\Middleware\RequestValidationMiddleware;
use apivalk\ApivalkPHP\Http\Request\ApivalkRequestInterface;
use apivalk\ApivalkPHP\Http\Response\AbstractApivalkResponse;
use apivalk\ApivalkPHP\Http\Response\BadValidationApivalkResponse;
use apivalk\ApivalkPHP\Documentation\ApivalkRequestDocumentation;
use apivalk\ApivalkPHP\Documentation\Property\AbstractProperty;
use apivalk\ApivalkPHP\Http\Request\Parameter\ParameterBag;
use apivalk\ApivalkPHP\Http\Request\Parameter\Parameter;
use apivalk\ApivalkPHP\Documentation\Property\Validator\AbstractValidator;
use apivalk\ApivalkPHP\Documentation\Property\Validator\ValidatorResult;

class RequestValidationMiddlewareTest extends TestCase
{
    public function testProcessSuccess(): void
    {
        $middleware = new RequestValidationMiddleware();
        
        $doc = new ApivalkRequestDocumentation();
        $prop = new class('test') extends AbstractProperty {
            public function getType(): string { return 'string'; }
            public function getPhpType(): string { return 'string'; }
            public function getDocumentationArray(): array { return []; }
        };
        $prop->setIsRequired(true);
        
        $doc->addQueryProperty($prop);

        $request = new class($doc) implements ApivalkRequestInterface {
            private static $d;
            public function __construct($d) { self::$d = $d; }
            public static function getDocumentation(): ApivalkRequestDocumentation { return self::$d; }
            public function populate(\apivalk\ApivalkPHP\Router\Route $route): void {}
            public function getMethod(): \apivalk\ApivalkPHP\Http\Method\MethodInterface { return $this->createMock(\apivalk\ApivalkPHP\Http\Method\MethodInterface::class); }
            public function header(): ParameterBag { return new ParameterBag(); }
            public function query(): ParameterBag { 
                $bag = new ParameterBag();
                $bag->set(new Parameter('test', 'val'));
                return $bag;
            }
            public function body(): ParameterBag { return new ParameterBag(); }
            public function path(): ParameterBag { return new ParameterBag(); }
            public function file(): \apivalk\ApivalkPHP\Http\Request\File\FileBag { return new \apivalk\ApivalkPHP\Http\Request\File\FileBag(); }
            public function getAuthIdentity(): ?\apivalk\ApivalkPHP\Security\AbstractAuthIdentity { return null; }
            public function setAuthIdentity(?\apivalk\ApivalkPHP\Security\AbstractAuthIdentity $authIdentity): void {}
        };

        $next = function ($req) {
            return $this->createMock(AbstractApivalkResponse::class);
        };

        $response = $middleware->process($request, 'SomeController', $next);
        $this->assertNotInstanceOf(BadValidationApivalkResponse::class, $response);
    }

    public function testProcessValidationError(): void
    {
        $middleware = new RequestValidationMiddleware();
        
        $doc = new ApivalkRequestDocumentation();
        $prop = new class('test') extends AbstractProperty {
            public function getType(): string { return 'string'; }
            public function getPhpType(): string { return 'string'; }
            public function getDocumentationArray(): array { return []; }
        };
        $prop->setIsRequired(true);
        
        $validator = $this->createMock(AbstractValidator::class);
        $validator->method('validate')->willReturn(new ValidatorResult(false, 'Invalid value'));
        $prop->addValidator($validator);
        
        $doc->addBodyProperty($prop);

        $request = new class($doc) implements ApivalkRequestInterface {
            private static $d;
            public function __construct($d) { self::$d = $d; }
            public static function getDocumentation(): ApivalkRequestDocumentation { return self::$d; }
            public function populate(\apivalk\ApivalkPHP\Router\Route $route): void {}
            public function getMethod(): \apivalk\ApivalkPHP\Http\Method\MethodInterface { return $this->createMock(\apivalk\ApivalkPHP\Http\Method\MethodInterface::class); }
            public function header(): ParameterBag { return new ParameterBag(); }
            public function query(): ParameterBag { return new ParameterBag(); }
            public function body(): ParameterBag { 
                $bag = new ParameterBag();
                $bag->set(new Parameter('test', 'val'));
                return $bag;
            }
            public function path(): ParameterBag { return new ParameterBag(); }
            public function file(): \apivalk\ApivalkPHP\Http\Request\File\FileBag { return new \apivalk\ApivalkPHP\Http\Request\File\FileBag(); }
            public function getAuthIdentity(): ?\apivalk\ApivalkPHP\Security\AbstractAuthIdentity { return null; }
            public function setAuthIdentity(?\apivalk\ApivalkPHP\Security\AbstractAuthIdentity $authIdentity): void {}
        };

        $next = function ($req) {
            return $this->createMock(AbstractApivalkResponse::class);
        };

        $response = $middleware->process($request, 'SomeController', $next);
        $this->assertInstanceOf(BadValidationApivalkResponse::class, $response);
        /** @var BadValidationApivalkResponse $response */
        $this->assertCount(1, $response->getErrors());
    }
}
