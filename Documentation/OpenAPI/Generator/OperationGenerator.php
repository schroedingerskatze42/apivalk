<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Documentation\OpenAPI\Generator;

use apivalk\ApivalkPHP\Documentation\ApivalkRequestDocumentation;
use apivalk\ApivalkPHP\Documentation\OpenAPI\Object\OperationObject;
use apivalk\ApivalkPHP\Http\Response\AbstractApivalkResponse;
use apivalk\ApivalkPHP\Http\Response\BadValidationApivalkResponse;
use apivalk\ApivalkPHP\Http\Response\MethodNotAllowedApivalkResponse;
use apivalk\ApivalkPHP\Http\Response\NotFoundApivalkResponse;
use apivalk\ApivalkPHP\Http\Response\TooManyRequestsApivalkResponse;
use apivalk\ApivalkPHP\Http\Response\UnauthorizedApivalkResponse;
use apivalk\ApivalkPHP\Router\Route;

class OperationGenerator
{
    public function generate(
        Route $route,
        ApivalkRequestDocumentation $requestDocumentation,
        array $responseClasses
    ): OperationObject {
        $parameterGenerator = new ParameterGenerator();
        $requestBodyGenerator = new RequestBodyGenerator();
        $responseGenerator = new ResponseGenerator();

        $parameters = [];
        foreach ($requestDocumentation->getPathProperties() as $pathProperty) {
            $parameters[] = $parameterGenerator->generate($pathProperty, 'path');
        }

        foreach ($requestDocumentation->getQueryProperties() as $pathProperty) {
            $parameters[] = $parameterGenerator->generate($pathProperty, 'query');
        }

        $responses = [];

        /** @var AbstractApivalkResponse $responseClass */
        foreach ($responseClasses as $responseClass) {
            $responses[] =
                $responseGenerator->generate((int)$responseClass::getStatusCode(), $responseClass::getDocumentation());
        }

        // Todo: Maybe define the default responses in all operations in apivalk configuration
        $responses[] = $responseGenerator->generate(
            BadValidationApivalkResponse::getStatusCode(),
            BadValidationApivalkResponse::getDocumentation()
        );
        $responses[] = $responseGenerator->generate(
            MethodNotAllowedApivalkResponse::getStatusCode(),
            MethodNotAllowedApivalkResponse::getDocumentation()
        );
        $responses[] = $responseGenerator->generate(
            NotFoundApivalkResponse::getStatusCode(),
            NotFoundApivalkResponse::getDocumentation()
        );
        $responses[] = $responseGenerator->generate(
            TooManyRequestsApivalkResponse::getStatusCode(),
            TooManyRequestsApivalkResponse::getDocumentation()
        );
        $responses[] = $responseGenerator->generate(
            UnauthorizedApivalkResponse::getStatusCode(),
            UnauthorizedApivalkResponse::getDocumentation()
        );

        return new OperationObject(
            $route->getMethod(),
            $route->getTags(),
            $route->getDescription(),
            $route->getDescription(),
            \sprintf('%s_%s', $route->getUrl(), $route->getMethod()->getName()),
            $parameters,
            $requestBodyGenerator->generate($requestDocumentation, $route),
            $responses,
            $route->getSecurityRequirements()
        );
    }
}
