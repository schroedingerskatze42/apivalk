<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Tests\PhpUnit\Documentation\Response;

use PHPUnit\Framework\TestCase;
use apivalk\ApivalkPHP\Documentation\Response\ErrorApivalkObject;
use apivalk\ApivalkPHP\Documentation\Property\AbstractPropertyCollection;

class ErrorApivalkObjectTest extends TestCase
{
    public function testErrorApivalkObject(): void
    {
        $object = new ErrorApivalkObject();
        $this->assertEquals('error', $object->getPropertyName());
        $this->assertEquals('Error', $object->getError());
        $this->assertEquals('error', $object->getName());

        $object->populateByArray(['name' => 'email', 'error' => 'Invalid']);
        $this->assertEquals('email', $object->getName());
        $this->assertEquals('Invalid', $object->getError());
        
        $this->assertInstanceOf(AbstractPropertyCollection::class, $object->getPropertyCollection());
    }
}
