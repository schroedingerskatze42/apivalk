<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Tests\PhpUnit\Documentation\OpenAPI\Generator;

use PHPUnit\Framework\TestCase;
use apivalk\ApivalkPHP\Documentation\OpenAPI\Generator\ParameterGenerator;
use apivalk\ApivalkPHP\Documentation\Property\StringProperty;

class ParameterGeneratorTest extends TestCase
{
    public function testParameterGenerator(): void
    {
        $generator = new ParameterGenerator();
        $prop = new StringProperty('name', 'Desc');
        $parameter = $generator->generate($prop, 'query');
        
        $this->assertEquals('name', $parameter->getName());
        $this->assertEquals('query', $parameter->getIn());
        $this->assertEquals('Desc', $parameter->getDescription());
    }
}
