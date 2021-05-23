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

    /**
    * @dataProvider fileProvider
    */
    public function testFormatting(string $file1, string $file2, string $format, string $expected): void
    {
        $output = genDiff(__DIR__ . $file1, __DIR__ . $file2, $format);
        $this->assertEquals($expected, $output);
    }

    public function fileProvider(): mixed
    {
        $this->expectedStylish = file_get_contents(FIXTURES_PATH . 'expectedStylish');
        $this->expectedPlain = file_get_contents(FIXTURES_PATH . 'expectedPlain');
        $this->expectedJson = file_get_contents(FIXTURES_PATH . 'expectedJson');

        return [
            ['/fixtures/file1.json','/fixtures/file2.json', 'stylish', $this->expectedStylish],
            ['/fixtures/file1.json','/fixtures/file2.yml', 'stylish', $this->expectedStylish],
            ['/fixtures/file1.yaml','/fixtures/file2.json', 'json', $this->expectedJson],
            ['/fixtures/file1.json','/fixtures/file2.yml', 'plain', $this->expectedPlain],
        ];
    }
}
