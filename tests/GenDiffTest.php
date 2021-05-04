<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class GenDiffTest extends TestCase
{
    public function testFlatStylishFormatting(): void
    {
        $expectedOutput = file_get_contents(__DIR__ . '/fixtures/expectedFlatJsonStylish');
        $actualOutput = genDiff(__DIR__ . '/fixtures/file1.json', __DIR__ . '/fixtures/file2.json');

        $this->assertEquals($expectedOutput, $actualOutput);
    }
}
