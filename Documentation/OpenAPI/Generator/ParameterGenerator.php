<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Documentation\OpenAPI\Generator;

use apivalk\ApivalkPHP\Documentation\OpenAPI\Object\ParameterObject;
use apivalk\ApivalkPHP\Documentation\OpenAPI\Object\SingleSchemaObject;
use apivalk\ApivalkPHP\Documentation\Property\AbstractProperty;

class ParameterGenerator
{
    public function generate(AbstractProperty $property, string $in): ParameterObject
    {
        return new ParameterObject(
            $property->getPropertyName(),
            $in,
            $property->getPropertyDescription(),
            $property->isRequired(),
            new SingleSchemaObject($property->getPropertyName(), $property->getType(), $property->isRequired())
        );
    }
}
