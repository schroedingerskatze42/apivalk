<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Http\Method;

class DeleteMethod implements MethodInterface
{
    public function getName(): string
    {
        return self::METHOD_DELETE;
    }
}
