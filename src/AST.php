<?php

declare(strict_types=1);

namespace Differ\AST;

use function Functional\sort;

function makeNode(string $type, string $key, mixed $oldValue, mixed $newValue): mixed
{
    return (object) ['type' => $type, 'key' => $key, 'oldValue' => $oldValue, 'newValue' => $newValue];
}

function genAST(object $firstFile, object $secondFile): object
{
    $firstFileKeys = array_keys((array) $firstFile);
    $seconFiledKeys = array_keys((array) $secondFile);
    $unionKeys = array_merge($firstFileKeys, array_diff($seconFiledKeys, $firstFileKeys));
    $sortedKeys = sort($unionKeys, fn($first, $second) => strcmp($first, $second));

    $AST = array_map(function ($key) use ($firstFile, $secondFile) {

        $value1 = $firstFile->$key ?? null;
        $value2 = $secondFile->$key ?? null;

        if (!property_exists($secondFile, (string) $key)) {
            return makeNode('removed', $key, $value1, null);
        }

        if (!property_exists($firstFile, (string) $key)) {
            return makeNode('added', $key, null, $value2);
        }

        if (is_object($value1) && is_object($value2)) {
            return makeNode('children', $key, genAST($value1, $value2), null);
        }

        if ($value1 === $value2) {
            return makeNode('unchanged', $key, $value1, $value2);
        }

        if ($value1 !== $value2) {
            return makeNode('changed', $key, $value1, $value2);
        }

        return null;
    }, $sortedKeys);

    return (object) $AST;
}
