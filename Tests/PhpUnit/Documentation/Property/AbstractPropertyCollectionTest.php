<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Tests\PhpUnit\Documentation\Property;

use PHPUnit\Framework\TestCase;
use apivalk\ApivalkPHP\Documentation\Property\AbstractPropertyCollection;
use apivalk\ApivalkPHP\Documentation\Property\StringProperty;

class AbstractPropertyCollectionTest extends TestCase
{
    public function testAbstractPropertyCollection()
    {
        $collection = new class(AbstractPropertyCollection::MODE_VIEW) extends AbstractPropertyCollection {
            public function __construct(string $mode)
            {
                $this->addProperty(new StringProperty('subProp', 'Sub Property'));
            }
        };

        $properties = iterator_to_array($collection->getIterator());
        $this->assertCount(1, $properties);
        $this->assertInstanceOf(StringProperty::class, $properties[0]);
        $this->assertEquals('subProp', $properties[0]->getPropertyName());
    }
}
