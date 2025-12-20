<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Http\Controller;

use apivalk\ApivalkPHP\Http\Request\ApivalkRequestInterface;
use apivalk\ApivalkPHP\Http\Response\AbstractApivalkResponse;
use apivalk\ApivalkPHP\Router\Route;

abstract class AbstractApivalkController
{
    abstract public static function getRoute(): Route;

    /**
     * @return class-string<ApivalkRequestInterface>
     */
    abstract public static function getRequestClass(): string;

    /**
     * @return array<class-string<AbstractApivalkResponse>>
     */
    abstract public static function getResponseClasses(): array;

    abstract public function __invoke(ApivalkRequestInterface $request): AbstractApivalkResponse;
}
