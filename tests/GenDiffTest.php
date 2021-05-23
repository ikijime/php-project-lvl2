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
        $this->assertEquals($expected, genDiff($file1, $file2, $format));
    }

    public function fileProvider(): mixed
    {
        $this->expectedStylish = file_get_contents(FIXTURES_PATH . 'expectedStylish');
        $this->expectedPlain = file_get_contents(FIXTURES_PATH . 'expectedPlain');
        $this->expectedJson = file_get_contents(FIXTURES_PATH . 'expectedJson');

        // todo: вынести пути файлов для сокращения повторений
        return [
            'Test Stylish with two json files' =>
                [FIXTURES_PATH . 'file1.json', FIXTURES_PATH . 'file2.json', 'stylish', $this->expectedStylish],
            'Test Stylish with two different file formats' =>
                [FIXTURES_PATH . 'file1.json', FIXTURES_PATH . 'file2.yml', 'stylish', $this->expectedStylish],
            'Test Json with two different file formats' =>
                [FIXTURES_PATH . 'file1.yaml', FIXTURES_PATH . 'file2.json', 'json', $this->expectedJson],
            'Test Plain with two different file formats' =>
                [FIXTURES_PATH . 'file1.json', FIXTURES_PATH . 'file2.yml', 'plain', $this->expectedPlain],
        ];
    }
}
