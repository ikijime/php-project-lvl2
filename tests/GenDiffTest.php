<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class GenDiffTest extends TestCase
{
    protected string $expectedFlatOutput;

    protected function setUp(): void
    {
        $this->expectedFlatOutput = file_get_contents(__DIR__ . '/fixtures/expectedFlatStylish');
    }
    
    public function test_flat_stylish_formatting_json(): void
    {
        $output = genDiff(__DIR__ . '/fixtures/file1.json', __DIR__ . '/fixtures/file2.json');
        $this->assertEquals($this->expectedFlatOutput, $output);
    }
    
    public function test_flat_stylish_formatting_yaml(): void
    {
        $output = genDiff(__DIR__ . '/fixtures/file1.yml', __DIR__ . '/fixtures/file2.yaml');
        $this->assertEquals($this->expectedFlatOutput, $output);
    }

    public function test_flat_stylish_formatting_mixed(): void
    {
        $output = genDiff(__DIR__ . '/fixtures/file1.json', __DIR__ . '/fixtures/file2.yaml');
        $this->assertEquals($this->expectedFlatOutput, $output);
    }
}
