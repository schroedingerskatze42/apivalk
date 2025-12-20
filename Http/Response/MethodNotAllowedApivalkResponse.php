<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Http\Response;

use apivalk\ApivalkPHP\Documentation\ApivalkResponseDocumentation;
use apivalk\ApivalkPHP\Documentation\Property\StringProperty;

class MethodNotAllowedApivalkResponse extends AbstractApivalkResponse
{
    public static function getDocumentation(): ApivalkResponseDocumentation
    {
        $responseDocumentation = new ApivalkResponseDocumentation();

        $responseDocumentation->setDescription('Method not allowed');
        $responseDocumentation->addProperty(new StringProperty('error', 'Error message'));

        return $responseDocumentation;
    }

    public static function getStatusCode(): int
    {
        return self::HTTP_405_METHOD_NOT_ALLOWED;
    }

    public function toArray(): array
    {
        return [
            'error' => 'Method not allowed'
        ];
    }
}
