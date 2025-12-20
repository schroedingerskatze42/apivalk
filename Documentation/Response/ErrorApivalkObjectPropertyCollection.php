<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Documentation\Response;

use apivalk\ApivalkPHP\Documentation\Property\AbstractPropertyCollection;
use apivalk\ApivalkPHP\Documentation\Property\StringProperty;

class ErrorApivalkObjectPropertyCollection extends AbstractPropertyCollection
{
    public function __construct(string $mode)
    {
        $this->addProperty(new StringProperty('name', 'The field name'));
        $this->addProperty(new StringProperty('error', 'The error message'));
    }
}
