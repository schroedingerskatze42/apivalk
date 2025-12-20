<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Tests\PhpUnit\Documentation\OpenAPI\Object;

use PHPUnit\Framework\TestCase;
use apivalk\ApivalkPHP\Documentation\OpenAPI\Object\PathsObject;
use apivalk\ApivalkPHP\Documentation\OpenAPI\Object\PathItemObject;

class PathsObjectTest extends TestCase
{
    public function testToArray(): void
    {
        $pathItem = new PathItemObject('Summary');
        $paths = new PathsObject('/users', $pathItem);
        
        $result = $paths->toArray();

        $this->assertArrayHasKey('/users', $result);
        $this->assertEquals('Summary', $result['/users']['summary']);
    }
}
