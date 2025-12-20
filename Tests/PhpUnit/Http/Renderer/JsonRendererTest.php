<?php

declare(strict_types=1);

namespace apivalk\ApivalkPHP\Tests\PhpUnit\Http\Renderer;

use PHPUnit\Framework\TestCase;
use apivalk\ApivalkPHP\Http\Renderer\JsonRenderer;
use apivalk\ApivalkPHP\Http\Response\AbstractApivalkResponse;
use apivalk\ApivalkPHP\Http\Response\ResponsePagination;

class JsonRendererTest extends TestCase
{
    public function testRender(): void
    {
        $response = new class extends AbstractApivalkResponse {
            public static function getDocumentation(): \apivalk\ApivalkPHP\Documentation\ApivalkResponseDocumentation { return new \apivalk\ApivalkPHP\Documentation\ApivalkResponseDocumentation(); }
            public static function getStatusCode(): int { return 200; }
            public function toArray(): array { return ['data' => 'test']; }
            public function getHeaders(): array { return ['X-Custom' => 'Value']; }
        };

        $renderer = new JsonRenderer();

        ob_start();
        @$renderer->render($response);
        $output = ob_get_clean();

        $this->assertEquals(json_encode(['data' => 'test']), $output);
    }

    public function testRenderWithPagination(): void
    {
        $pagination = $this->createMock(ResponsePagination::class);
        $pagination->method('toArray')->willReturn(['page' => 1, 'total_pages' => 10]);

        $response = new class($pagination) extends AbstractApivalkResponse {
            private $pagination;
            public function __construct($pagination) { $this->pagination = $pagination; }
            public static function getDocumentation(): \apivalk\ApivalkPHP\Documentation\ApivalkResponseDocumentation { return new \apivalk\ApivalkPHP\Documentation\ApivalkResponseDocumentation(); }
            public static function getStatusCode(): int { return 200; }
            public function toArray(): array { return ['data' => []]; }
            public function getHeaders(): array { return []; }
            public function getResponsePagination(): ?ResponsePagination { return $this->pagination; }
        };

        $renderer = new JsonRenderer();

        ob_start();
        @$renderer->render($response);
        $output = ob_get_clean();

        $expected = [
            'data' => [],
            'pagination' => ['page' => 1, 'total_pages' => 10]
        ];
        $this->assertEquals(json_encode($expected), $output);
    }
}
