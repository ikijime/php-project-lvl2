<?php

declare(strict_types=1);

namespace Differ\Differ;

use function Differ\Parsers\parse;
use function Differ\AST\genAST;
use function Differ\Formatters\format;

const DEFAULT_FORMAT = "stylish";

function genDiff(string $filePath1, string $filePath2, string $format = DEFAULT_FORMAT): string
{
    $AST = genAST(parse($filePath1), parse($filePath2));

    return format($AST, $format);
}
