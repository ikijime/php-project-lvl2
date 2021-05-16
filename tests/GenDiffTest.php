<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class GenDiffTest extends TestCase
{
    public string|false $expectedStylishOutput;
    public string|false $expectedPlainOutput;

    protected function setUp(): void
    {
        $this->expectedStylishOutput = file_get_contents(__DIR__ . '/fixtures/expectedStylish');
        $this->expectedPlainOutput = file_get_contents(__DIR__ . '/fixtures/expectedPlain');
    }

    /**
    * @dataProvider fileProvider
    */
    public function testStylishFormatting(string $file1, string $file2): void
    {
        $output = genDiff(__DIR__ . $file1, __DIR__ . $file2, 'stylish');
        $this->assertEquals($this->expectedStylishOutput, $output);
    }

    /**
    * @dataProvider fileProvider
    */
    public function testPlainFormatting(string $file1, string $file2): void
    {
        $output = genDiff(__DIR__ . $file1, __DIR__ . $file2, 'plain');
        $this->assertEquals($this->expectedPlainOutput, $output);
    }

    public function fileProvider(): mixed
    {
        return [
            'json files' => ['/fixtures/file1.json','/fixtures/file2.json'],
            'yaml files' => ['/fixtures/file1.yaml','/fixtures/file2.yml'],
            'mixed files' => ['/fixtures/file1.json','/fixtures/file2.yml'],
        ];
    }
}
