<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Middleware;

use apivalk\ApivalkPHP\Http\Request\ApivalkRequestInterface;
use apivalk\ApivalkPHP\Http\Response\AbstractApivalkResponse;

interface MiddlewareInterface
{
    public function process(
        ApivalkRequestInterface $request,
        string $controllerClass,
        callable $next
    ): AbstractApivalkResponse;
}
