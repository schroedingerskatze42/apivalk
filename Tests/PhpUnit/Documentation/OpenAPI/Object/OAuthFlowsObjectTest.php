<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Tests\PhpUnit\Documentation\OpenAPI\Object;

use PHPUnit\Framework\TestCase;
use apivalk\ApivalkPHP\Documentation\OpenAPI\Object\OAuthFlowsObject;
use apivalk\ApivalkPHP\Documentation\OpenAPI\Object\OAuthFlowObject;

class OAuthFlowsObjectTest extends TestCase
{
    public function testToArray(): void
    {
        $flow = $this->createMock(OAuthFlowObject::class);
        $flow->method('toArray')->willReturn([
            'authorizationUrl' => 'https://example.com/auth',
            'tokenUrl' => 'https://example.com/token',
            'scopes' => []
        ]);

        $flows = new OAuthFlowsObject($flow, null, null, null);
        
        $result = $flows->toArray();

        $this->assertArrayHasKey('implicit', $result);
        $this->assertEquals('https://example.com/auth', $result['implicit']['authorizationUrl']);
        
        $this->assertSame($flow, $flows->getImplicit());
        $this->assertNull($flows->getPassword());
        $this->assertNull($flows->getClientCredentials());
        $this->assertNull($flows->getAuthorizationCode());
    }
}
