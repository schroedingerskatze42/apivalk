<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Tests\PhpUnit\Documentation\Response;

use PHPUnit\Framework\TestCase;
use apivalk\ApivalkPHP\Documentation\Response\ErrorApivalkObjectPropertyCollection;
use apivalk\ApivalkPHP\Documentation\Property\AbstractPropertyCollection;

class ErrorApivalkObjectPropertyCollectionTest extends TestCase
{
    public function testCollection(): void
    {
        $collection = new ErrorApivalkObjectPropertyCollection(AbstractPropertyCollection::MODE_VIEW);
        $properties = iterator_to_array($collection);
        
        $this->assertCount(2, $properties);
        $this->assertEquals('name', $properties[0]->getPropertyName());
        $this->assertEquals('error', $properties[1]->getPropertyName());
    }
}
