<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Http\Controller;

interface ApivalkControllerFactoryInterface
{
    public function create(string $controllerClass): AbstractApivalkController;
}
