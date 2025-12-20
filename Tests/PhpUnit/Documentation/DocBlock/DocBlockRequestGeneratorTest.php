<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Tests\PhpUnit\Documentation\DocBlock;

use PHPUnit\Framework\TestCase;
use apivalk\ApivalkPHP\Documentation\DocBlock\DocBlockRequestGenerator;
use apivalk\ApivalkPHP\Documentation\ApivalkRequestDocumentation;
use apivalk\ApivalkPHP\Http\Request\AbstractApivalkRequest;
use apivalk\ApivalkPHP\Documentation\Property\StringProperty;
use apivalk\ApivalkPHP\Documentation\Property\NumberProperty;

class TestRequest extends AbstractApivalkRequest {
    public static function getDocumentation(): ApivalkRequestDocumentation {
        $doc = new ApivalkRequestDocumentation();
        $doc->addBodyProperty(new StringProperty('name'));
        $doc->addQueryProperty(new NumberProperty('id'));
        $doc->addPathProperty(new StringProperty('slug'));
        return $doc;
    }
}

class DocBlockRequestGeneratorTest extends TestCase
{
    public function testGenerate(): void
    {
        $generator = new DocBlockRequestGenerator();
        $request = new TestRequest();

        $docBlockRequest = $generator->generate($request);

        $this->assertEquals('TestRequestBodyShape', $docBlockRequest->getBodyShape()->getClassName());
        $this->assertEquals('TestRequestPathShape', $docBlockRequest->getPathShape()->getClassName());
        $this->assertEquals('TestRequestQueryShape', $docBlockRequest->getQueryShape()->getClassName());

        $bodyString = $docBlockRequest->getBodyShape()->toString('App\\Shape');
        $this->assertStringContainsString('@property-read string $name', $bodyString);

        $queryString = $docBlockRequest->getQueryShape()->toString('App\\Shape');
        $this->assertStringContainsString('@property-read float $id', $queryString);

        $pathString = $docBlockRequest->getPathShape()->toString('App\\Shape');
        $this->assertStringContainsString('@property-read string $slug', $pathString);
    }
}
