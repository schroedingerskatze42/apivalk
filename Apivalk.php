<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP;

use apivalk\ApivalkPHP\Http\Response\AbstractApivalkResponse;
use apivalk\ApivalkPHP\Middleware\MiddlewareStack;
use apivalk\ApivalkPHP\Http\Renderer\RendererInterface;
use apivalk\ApivalkPHP\Router\AbstractRouter;
use Psr\Container\ContainerInterface;

class Apivalk
{
    /** @var MiddlewareStack */
    private $middlewareStack;
    /** @var RendererInterface */
    private $renderer;
    /** @var AbstractRouter */
    private $router;
    /** @var ContainerInterface|null */
    private $container;

    public function __construct(ApivalkConfiguration $apivalkConfiguration)
    {
        $this->middlewareStack = $apivalkConfiguration->getMiddlewareStack();
        $this->renderer = $apivalkConfiguration->getRenderer();
        $this->router = $apivalkConfiguration->getRouter();
        $this->container = $apivalkConfiguration->getContainer();

        if ($apivalkConfiguration->getExceptionHandler() !== null) {
            set_exception_handler($apivalkConfiguration->getExceptionHandler());
        }
    }

    public function run(): AbstractApivalkResponse
    {
        return $this->router->dispatch($this->middlewareStack);
    }

    public function getMiddlewareStack(): MiddlewareStack
    {
        return $this->middlewareStack;
    }

    public function getRenderer(): RendererInterface
    {
        return $this->renderer;
    }

    public function getRouter(): AbstractRouter
    {
        return $this->router;
    }

    public function getContainer(): ?ContainerInterface
    {
        return $this->container;
    }
}
