<?php

declare(strict_types=1);

namespace apivalk\apivalk\Tests\PhpUnit\Documentation\DocBlock;

use PHPUnit\Framework\TestCase;
use apivalk\apivalk\Documentation\DocBlock\DocBlockGenerator;
use apivalk\apivalk\Http\Request\AbstractApivalkRequest;
use apivalk\apivalk\Documentation\ApivalkRequestDocumentation;

class DocBlockGeneratorTest extends TestCase
{
    private $tempDir;

    protected function setUp(): void
    {
        $this->tempDir = sys_get_temp_dir() . '/apivalk_docblock_test_' . uniqid('', true);
        mkdir($this->tempDir);
        mkdir($this->tempDir . '/Request');
    }

    protected function tearDown(): void
    {
        $this->removeDirectory($this->tempDir);
    }

    private function removeDirectory(string $path): void
    {
        if (is_dir($path)) {
            $files = array_diff(scandir($path), ['.', '..']);
            foreach ($files as $file) {
                $this->removeDirectory($path . '/' . $file);
            }
            rmdir($path);
        } else {
            unlink($path);
        }
    }

    public function testRun(): void
    {
        $uniqueClassName = 'TestRequest_' . str_replace('.', '_', uniqid('', true));
        $requestFile = $this->tempDir . '/Request/' . $uniqueClassName . '.php';
        $content = <<<PHP
<?php

namespace TestNamespace\Request;

use apivalk\apivalk\Http\Request\AbstractApivalkRequest;
use apivalk\apivalk\Documentation\ApivalkRequestDocumentation;

class {$uniqueClassName} extends AbstractApivalkRequest
{
    public static function getDocumentation(): ApivalkRequestDocumentation
    {
        return new ApivalkRequestDocumentation();
    }
}
PHP;
        file_put_contents($requestFile, $content);
        require_once $requestFile;

        $generator = new DocBlockGenerator();
        
        // We need to use output buffering because the generator echoes stuff
        ob_start();
        $generator->run($this->tempDir . '/Request', 'TestNamespace\\Request');
        $output = ob_get_clean();

        $this->assertStringContainsString('✔ DocBlocks & Shapes generated', $output);
        
        // Verify request file was updated
        $updatedContent = file_get_contents($requestFile);
        $this->assertStringContainsString('/**', $updatedContent);
        $this->assertStringContainsString('@method', $updatedContent);
        $this->assertStringContainsString('query()', $updatedContent);
        $this->assertStringContainsString('path()', $updatedContent);
        $this->assertStringContainsString('body()', $updatedContent);

        // Verify shape files were created
        $this->assertFileExists($this->tempDir . '/Request/Shape/' . $uniqueClassName . 'PathShape.php');
        $this->assertFileExists($this->tempDir . '/Request/Shape/' . $uniqueClassName . 'QueryShape.php');
        $this->assertFileExists($this->tempDir . '/Request/Shape/' . $uniqueClassName . 'BodyShape.php');
    }

    public function testRunInvalidDirectory(): void
    {
        $this->expectException(\RuntimeException::class);
        $generator = new DocBlockGenerator();
        $generator->run('/invalid/path', 'Namespace');
    }
}
