<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

const FIXTURES_PATH = __DIR__ . '/fixtures/';

class GenDiffTest extends TestCase
{
    public string|false $expectedStylish;
    public string|false $expectedPlain;
    public string|false $expectedJson;

    protected function setUp(): void
    {
        $this->expectedStylish = file_get_contents(FIXTURES_PATH . 'expectedStylish');
        $this->expectedPlain = file_get_contents(FIXTURES_PATH . 'expectedPlain');
        $this->expectedJson = file_get_contents(FIXTURES_PATH . 'expectedJson');
    }

    /**
    * @dataProvider fileProvider
    */
    public function testStylishFormatting(string $file1, string $file2): void
    {
        $output = genDiff(__DIR__ . $file1, __DIR__ . $file2, 'stylish');
        $this->assertEquals($this->expectedStylish, $output);
    }

    /**
    * @dataProvider fileProvider
    */
    public function testPlainFormatting(string $file1, string $file2): void
    {
        $output = genDiff(__DIR__ . $file1, __DIR__ . $file2, 'plain');
        $this->assertEquals($this->expectedPlain, $output);
    }

    /**
    * @dataProvider fileProvider
    */
    public function testJsonFormatting(string $file1, string $file2): void
    {
        $output = genDiff(__DIR__ . $file1, __DIR__ . $file2, 'json');
        $this->assertEquals($this->expectedJson, $output);
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
