<?php

declare(strict_types=1);

namespace Differ\Formatters;

use const Differ\Differ\DEFAULT_FORMAT;

function format(object $AST, string $format = DEFAULT_FORMAT): string
{
    $formats = [
        'stylish' => fn($AST) => stylish($AST),
        'plain' => fn($AST) => plain($AST),
        'json' => fn($AST) => json($AST)
    ];

    return $formats[$format]($AST);
}
