<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Http\Response;

use apivalk\ApivalkPHP\Documentation\ApivalkResponseDocumentation;
use apivalk\ApivalkPHP\Documentation\Property\StringProperty;

class NotFoundApivalkResponse extends AbstractApivalkResponse
{
    public static function getDocumentation(): ApivalkResponseDocumentation
    {
        $responseDocumentation = new ApivalkResponseDocumentation();

        $responseDocumentation->setDescription('Not found');
        $responseDocumentation->addProperty(new StringProperty('error', 'Error message'));

        return $responseDocumentation;
    }

    public static function getStatusCode(): int
    {
        return self::HTTP_404_NOT_FOUND;
    }

    public function toArray(): array
    {
        return [
            'error' => 'Not found'
        ];
    }
}
