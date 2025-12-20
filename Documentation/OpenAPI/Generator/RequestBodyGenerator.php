<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Documentation\OpenAPI\Generator;

use apivalk\ApivalkPHP\Documentation\ApivalkRequestDocumentation;
use apivalk\ApivalkPHP\Documentation\OpenAPI\Object\RequestBodyObject;
use apivalk\ApivalkPHP\Documentation\OpenAPI\Object\SchemaObject;
use apivalk\ApivalkPHP\Router\Route;

class RequestBodyGenerator
{
    public function generate(ApivalkRequestDocumentation $requestDocumentation, Route $route): RequestBodyObject
    {
        $mediaTypeGenerator = new MediaTypeGenerator();

        $schema = new SchemaObject('object', true, $requestDocumentation->getBodyProperties());

        return new RequestBodyObject(
            $mediaTypeGenerator->generate('application/json', $schema),
            $route->getDescription(),
            true
        );
    }
}
