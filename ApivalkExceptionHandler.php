<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP;

use apivalk\ApivalkPHP\Http\Renderer\JsonRenderer;
use apivalk\ApivalkPHP\Http\Response\InternalServerErrorApivalkResponse;

class ApivalkExceptionHandler
{
    public static function handle(\Throwable $t): void
    {
        $response = new InternalServerErrorApivalkResponse();

        (new JsonRenderer())->render($response);
    }
}
