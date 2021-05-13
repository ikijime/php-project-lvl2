<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class GenDiffTest extends TestCase
{
    public string|false $expectedFlatOutput;

    protected function setUp(): void
    {
        $this->expectedFlatOutput = file_get_contents(__DIR__ . '/fixtures/expectedStylish');
    }

    public function testFlatStylishFormattingJson(): void
    {
        $output = genDiff(__DIR__ . '/fixtures/file1.json', __DIR__ . '/fixtures/file2.json');
        $this->assertEquals($this->expectedFlatOutput, $output);
    }
}
