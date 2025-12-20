<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Http\Response;

use apivalk\ApivalkPHP\Documentation\ApivalkResponseDocumentation;

class DeletedApivalkResponse extends AbstractApivalkResponse
{
    public static function getDocumentation(): ApivalkResponseDocumentation
    {
        $responseDocumentation = new ApivalkResponseDocumentation();

        $responseDocumentation->setDescription('Deleted');

        return $responseDocumentation;
    }

    public static function getStatusCode(): int
    {
        return self::HTTP_204_NO_CONTENT;
    }

    public function toArray(): array
    {
        return [];
    }
}
