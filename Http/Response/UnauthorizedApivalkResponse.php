<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Http\Response;

use apivalk\ApivalkPHP\Documentation\ApivalkResponseDocumentation;
use apivalk\ApivalkPHP\Documentation\Property\StringProperty;

class UnauthorizedApivalkResponse extends AbstractApivalkResponse
{
    public static function getDocumentation(): ApivalkResponseDocumentation
    {
        $responseDocumentation = new ApivalkResponseDocumentation();

        $responseDocumentation->setDescription('Unauthorized');
        $responseDocumentation->addProperty(new StringProperty('error', 'Error message'));

        return $responseDocumentation;
    }

    public static function getStatusCode(): int
    {
        return self::HTTP_401_UNAUTHORIZED;
    }

    public function toArray(): array
    {
        return [
            'error' => 'Unauthorized'
        ];
    }
}
