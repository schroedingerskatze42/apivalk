<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Documentation\DocBlock;

use apivalk\ApivalkPHP\Http\Request\AbstractApivalkRequest;

final class DocBlockRequestGenerator
{
    public function generate(AbstractApivalkRequest $abstractApivalkRequest): DocBlockRequest
    {
        $documentation = $abstractApivalkRequest::getDocumentation();

        $requestName = (new \ReflectionClass($abstractApivalkRequest))->getShortName();

        $bodyShape = new DocBlockShape($requestName, 'Body');
        $pathShape = new DocBlockShape($requestName, 'Path');
        $queryShape = new DocBlockShape($requestName, 'Query');

        foreach ($documentation->getBodyProperties() as $property) {
            $bodyShape->addProperty($property);
        }

        foreach ($documentation->getPathProperties() as $property) {
            $pathShape->addProperty($property);
        }

        foreach ($documentation->getQueryProperties() as $property) {
            $queryShape->addProperty($property);
        }

        return new DocBlockRequest(
            $bodyShape,
            $pathShape,
            $queryShape
        );
    }
}
