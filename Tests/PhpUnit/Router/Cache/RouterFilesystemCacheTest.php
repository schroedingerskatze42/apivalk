<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Tests\PhpUnit\Router\Cache;

use PHPUnit\Framework\TestCase;
use apivalk\ApivalkPHP\Router\Cache\RouterFilesystemCache;
use apivalk\ApivalkPHP\Util\ClassLocator;
use apivalk\ApivalkPHP\Router\Cache\RouterCacheCollection;
use apivalk\ApivalkPHP\Http\Controller\AbstractApivalkController;
use apivalk\ApivalkPHP\Router\Route;

class RouterFilesystemCacheTest extends TestCase
{
    private $tempDir;

    protected function setUp(): void
    {
        $this->tempDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'apivalk_test_' . uniqid();
        if (!is_dir($this->tempDir)) {
            mkdir($this->tempDir, 0777, true);
        }
    }

    protected function tearDown(): void
    {
        if (is_dir($this->tempDir)) {
            $this->removeDir($this->tempDir);
        }
    }

    private function removeDir(string $dir): void
    {
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            (is_dir($path)) ? $this->removeDir($path) : unlink($path);
        }
        rmdir($dir);
    }

    public function testGetRouterCacheCollectionFromExistingFile(): void
    {
        $locator = new ClassLocator($this->tempDir, 'Dummy');
        $cache = new RouterFilesystemCache($this->tempDir, $locator);
        
        $json = json_encode([
            [
                'controllerClass' => 'TestController',
                'route' => [
                    'url' => '/test',
                    'method' => 'GET',
                    'description' => null,
                    'tags' => [],
                    'securityRequirements' => []
                ]
            ]
        ]);
        
        file_put_contents($cache->getCacheFilePath(), $json);
        
        $collection = $cache->getRouterCacheCollection();
        $this->assertCount(1, $collection->getRouteCacheEntries());
        $this->assertEquals('TestController', $collection->getRouteCacheEntries()[0]->getControllerClass());
    }

    public function testBuildRouteCache(): void
    {
        $apiDir = $this->tempDir . DIRECTORY_SEPARATOR . 'Api';
        mkdir($apiDir);
        $controllerFile = $apiDir . DIRECTORY_SEPARATOR . 'CacheTestController.php';
        file_put_contents($controllerFile, '<?php namespace apivalk\ApivalkPHP\Tests\PhpUnit\Router\Cache\Api; class CacheTestController extends \apivalk\ApivalkPHP\Http\Controller\AbstractApivalkController { 
            public static function getRoute(): \apivalk\ApivalkPHP\Router\Route { return new \apivalk\ApivalkPHP\Router\Route("/dynamic", new \apivalk\ApivalkPHP\Http\Method\GetMethod()); }
            public static function getRequestClass(): string { return ""; }
            public static function getResponseClasses(): array { return []; }
            public function __invoke(\apivalk\ApivalkPHP\Http\Request\ApivalkRequestInterface $request): \apivalk\ApivalkPHP\Http\Response\AbstractApivalkResponse { return $this->createMock(\apivalk\ApivalkPHP\Http\Response\AbstractApivalkResponse::class); }
        }');
        
        require_once $controllerFile;

        $locator = new ClassLocator($apiDir, 'apivalk\\ApivalkPHP\\Tests\\PhpUnit\\Router\\Cache\\Api');

        $cache = new RouterFilesystemCache($this->tempDir, $locator);
        $collection = $cache->getRouterCacheCollection();
        
        $this->assertCount(1, $collection->getRouteCacheEntries());
        $this->assertEquals('/dynamic', $collection->getRouteCacheEntries()[0]->getRoute()->getUrl());
        $this->assertFileExists($cache->getCacheFilePath());
    }
}
