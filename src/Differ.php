<?php

declare(strict_types=1);

namespace Differ\Differ;

use function Differ\Formatters\stylish;
use function Differ\Parsers\parse;
use function Differ\AST\genAST;

function genDiff(string $filePath1, string $filePath2, string $format = 'stylish'): string
{
    $AST = genAST(parse($filePath1), parse($filePath2));

    $formats = [
        'stylish' => fn($AST) => stylish($AST)
    ];

    return $formats[$format]($AST);
}
