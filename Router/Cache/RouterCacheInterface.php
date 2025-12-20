<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Router\Cache;

interface RouterCacheInterface
{
    public function getRouterCacheCollection(): RouterCacheCollection;
}
