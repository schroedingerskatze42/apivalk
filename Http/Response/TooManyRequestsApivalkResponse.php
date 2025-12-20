<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Http\Response;

use apivalk\ApivalkPHP\Documentation\ApivalkResponseDocumentation;
use apivalk\ApivalkPHP\Documentation\Property\StringProperty;

class TooManyRequestsApivalkResponse extends AbstractApivalkResponse
{
    public static function getDocumentation(): ApivalkResponseDocumentation
    {
        $responseDocumentation = new ApivalkResponseDocumentation();

        $responseDocumentation->setDescription('Too many requests');
        $responseDocumentation->addProperty(new StringProperty('error', 'Error message'));

        return $responseDocumentation;
    }

    public static function getStatusCode(): int
    {
        return self::HTTP_429_TOO_MANY_REQUESTS;
    }

    public function toArray(): array
    {
        return [
            'error' => 'Too many requests'
        ];
    }
}
