<?php

declare(strict_types=1);

namespace Differ\AST;

use function Funct\Collection\union;
use function Funct\Collection\sortBy;

function makeNode(string $type, string $key, mixed $oldValue, mixed $newValue): mixed
{
    return (object) ['type' => $type, 'key' => $key, 'oldValue' => $oldValue, 'newValue' => $newValue];
}

function genAST(object $firstFile, object $secondFile): object
{
    $keys = union(
        array_keys(get_object_vars($firstFile)),
        array_keys(get_object_vars($secondFile))
    );
    $sortedKeys = sortBy($keys, fn($key) => $key);

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
            return makeNode('tree', $key, genAST($value1, $value2), null);
        }

        if ($value1 === $value2) {
            return makeNode('unchanged', $key, $value1, $value2);
        }

        if ($value1 !== $value2) {
            return makeNode('changed', $key, $value1, $value2);
        }

        return;
    }, $sortedKeys);

    return (object) $AST;
}
