<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Tests\PhpUnit\Documentation\OpenAPI\Object;

use PHPUnit\Framework\TestCase;
use apivalk\ApivalkPHP\Documentation\OpenAPI\Object\ContactObject;

class ContactObjectTest extends TestCase
{
    public function testToArray(): void
    {
        $contact = new ContactObject('Name', 'https://example.com', 'test@example.com');
        
        $expected = [
            'name' => 'Name',
            'url' => 'https://example.com',
            'email' => 'test@example.com'
        ];

        $this->assertEquals($expected, $contact->toArray());
        $this->assertEquals('Name', $contact->getName());
        $this->assertEquals('https://example.com', $contact->getUrl());
        $this->assertEquals('test@example.com', $contact->getEmail());
    }
}
