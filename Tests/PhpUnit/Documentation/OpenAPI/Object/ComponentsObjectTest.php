<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Tests\PhpUnit\Documentation\OpenAPI\Object;

use PHPUnit\Framework\TestCase;
use apivalk\ApivalkPHP\Documentation\OpenAPI\Object\ComponentsObject;
use apivalk\ApivalkPHP\Documentation\OpenAPI\Object\SchemaObject;
use apivalk\ApivalkPHP\Documentation\OpenAPI\Object\SecuritySchemeObject;

class ComponentsObjectTest extends TestCase
{
    public function testToArray(): void
    {
        $components = new ComponentsObject();
        $schema = new SchemaObject('object');
        $components->setSchemas(['User' => $schema]);
        
        $securityScheme = new SecuritySchemeObject(
            'http',
            'bearerAuth',
            null,
            null,
            'bearer',
            'JWT',
            null,
            null
        );
        $components->setSecuritySchemes([$securityScheme]);

        $result = $components->toArray();

        $this->assertArrayHasKey('schemas', $result);
        $this->assertArrayHasKey('User', $result['schemas']);
        $this->assertArrayHasKey('securitySchemes', $result);
        $this->assertArrayHasKey('bearerAuth', $result['securitySchemes']);
    }
}
