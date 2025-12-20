<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Router;

use apivalk\ApivalkPHP\Http\Controller\ApivalkControllerFactoryInterface;
use apivalk\ApivalkPHP\Http\Controller\ApivalkControllerFactory;
use apivalk\ApivalkPHP\Http\Response\AbstractApivalkResponse;
use apivalk\ApivalkPHP\Middleware\MiddlewareStack;
use apivalk\ApivalkPHP\Router\Cache\RouterCacheInterface;

abstract class AbstractRouter
{
    /** @var RouterCacheInterface */
    private $routerCache;

    /** @var ApivalkControllerFactoryInterface */
    private $controllerFactory;

    abstract public function dispatch(MiddlewareStack $middlewareStack): AbstractApivalkResponse;

    public function __construct(RouterCacheInterface $routerCache, ?ApivalkControllerFactoryInterface $controllerFactory = null)
    {
        if ($controllerFactory === null) {
            $controllerFactory = new ApivalkControllerFactory();
        }

        $this->controllerFactory = $controllerFactory;
        $this->routerCache = $routerCache;
    }

    public function getRouterCache(): RouterCacheInterface
    {
        return $this->routerCache;
    }

    public function getControllerFactory(): ApivalkControllerFactoryInterface
    {
        return $this->controllerFactory;
    }
}
