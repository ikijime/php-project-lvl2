<?php

declare(strict_types=1);

namespace Differ\Formatters;

use function Differ\Formatters\stylish;
use function Differ\Formatters\plain;

function format(object $AST, string $format = DEFAULT_FORMAT): string
{
    $formats = [
        'stylish' => fn($AST) => stylish($AST),
        'plain' => fn($AST) => plain($AST)
    ];

    return $formats[$format]($AST);
}
