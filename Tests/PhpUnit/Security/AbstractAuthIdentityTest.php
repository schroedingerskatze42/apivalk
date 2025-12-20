<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Tests\PhpUnit\Security;

use PHPUnit\Framework\TestCase;
use apivalk\ApivalkPHP\Security\AbstractAuthIdentity;

class AbstractAuthIdentityTest extends TestCase
{
    public function testAbstractAuthIdentity(): void
    {
        $identity = new class extends AbstractAuthIdentity {
            public function getGrantedScopes(): array {
                return ['read', 'write'];
            }
        };

        $this->assertEquals(['read', 'write'], $identity->getGrantedScopes());
    }
}
