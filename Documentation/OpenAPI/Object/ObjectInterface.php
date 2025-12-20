<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Documentation\OpenAPI\Object;

interface ObjectInterface
{
    public function toArray(): array;
}
