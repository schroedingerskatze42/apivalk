<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Security;

abstract class AbstractAuthIdentity
{
    /** @return string[] */
    abstract public function getGrantedScopes(): array;
}
