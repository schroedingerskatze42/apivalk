<?php

declare(strict_types=1);

namespace apivalk\apivalk\Tests\PhpUnit;

use PHPUnit\Framework\TestCase;
use apivalk\apivalk\ApivalkExceptionHandler;

class ApivalkExceptionHandlerTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testHandle(): void
    {
        // We use output buffering to capture the response from JsonRenderer
        ob_start();
        ApivalkExceptionHandler::handle(new \Exception("Test Exception"));
        $output = ob_get_clean();

        $this->assertJson($output);
        $data = json_decode($output, true);
        $this->assertEquals("We've run into an unknown error, please try again later.", $data['error']);
    }
}
