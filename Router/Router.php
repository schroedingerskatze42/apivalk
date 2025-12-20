<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Router;

use apivalk\ApivalkPHP\Http\Controller\AbstractApivalkController;
use apivalk\ApivalkPHP\Http\Request\ApivalkRequestInterface;
use apivalk\ApivalkPHP\Http\Response\AbstractApivalkResponse;
use apivalk\ApivalkPHP\Http\Response\MethodNotAllowedApivalkResponse;
use apivalk\ApivalkPHP\Http\Response\NotFoundApivalkResponse;
use apivalk\ApivalkPHP\Middleware\MiddlewareStack;
use apivalk\ApivalkPHP\Router\Cache\RouterCacheEntry;

class Router extends AbstractRouter
{
    private function getServerRequestMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    private function getServerRequestUrlPath(): string
    {
        return parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    }

    public function dispatch(MiddlewareStack $middlewareStack): AbstractApivalkResponse
    {
        $requestMethod = $this->getServerRequestMethod();
        $requestUrlPath = $this->getServerRequestUrlPath();

        $routeCacheEntries = $this->findMatchingRouteCacheEntries($requestUrlPath);
        if (\count($routeCacheEntries) === 0) {
            return new NotFoundApivalkResponse();
        }

        if (!\array_key_exists($requestMethod, $routeCacheEntries)) {
            return new MethodNotAllowedApivalkResponse();
        }

        $request = $this->buildRequestByRouteCacheEntry($routeCacheEntries[$requestMethod]);
        $controller = $this->buildControllerByRouteCacheEntry($routeCacheEntries[$requestMethod]);

        return $middlewareStack->handle($request, $controller);
    }

    private function buildControllerByRouteCacheEntry(RouterCacheEntry $routerCacheEntry): AbstractApivalkController
    {
        $controllerClass = $routerCacheEntry->getControllerClass();

        return $this->getControllerFactory()->create($controllerClass);
    }

    private function buildRequestByRouteCacheEntry(RouterCacheEntry $routerCacheEntry): ApivalkRequestInterface
    {
        /** @var class-string<AbstractApivalkController> $controllerClass */
        $controllerClass = $routerCacheEntry->getControllerClass();
        /** @var class-string<ApivalkRequestInterface> $requestClass */
        $requestClass = $controllerClass::getRequestClass();

        $request = new $requestClass();
        $request->populate($routerCacheEntry->getRoute());

        return $request;
    }

    /**
     * @return array<string, RouterCacheEntry> An array containing the matching routes. Key is the method name.
     */
    private function findMatchingRouteCacheEntries(string $requestUrlPath): array
    {
        $routes = [];

        foreach ($this->getRouterCache()->getRouterCacheCollection()->getRouteCacheEntries() as $routeCacheEntry) {
            if (preg_match($routeCacheEntry->getRegex(), $requestUrlPath)) {
                $routes[$routeCacheEntry->getRoute()->getMethod()->getName()] = $routeCacheEntry;
            }
        }

        return $routes;
    }
}
