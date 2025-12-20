<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Documentation\OpenAPI\Generator;

use apivalk\ApivalkPHP\Documentation\OpenAPI\Object\MediaTypeObject;
use apivalk\ApivalkPHP\Documentation\OpenAPI\Object\SchemaObject;

class MediaTypeGenerator
{
    public function generate(string $mediaType, SchemaObject $schemaObject): MediaTypeObject
    {
        return new MediaTypeObject(
            $schemaObject,
            $mediaType
        );
    }
}
