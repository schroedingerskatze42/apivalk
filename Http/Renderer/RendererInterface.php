<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Http\Renderer;

use apivalk\ApivalkPHP\Http\Response\AbstractApivalkResponse;

interface RendererInterface
{
    public function render(AbstractApivalkResponse $response): void;
}
