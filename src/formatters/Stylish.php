<?php

declare(strict_types=1);

namespace Differ\Formatters;

use function Funct\Collection\flattenAll;

const TAB_WIDTH = 4;

function makeIndent(int $depth): string
{
    return str_repeat(' ', TAB_WIDTH * $depth);
}

function parseValue(mixed $value, int $depth): string
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if (is_null($value)) {
        return 'null';
    }

    if (!is_object($value)) {
        return (string) $value;
    }

    if (is_object($value)) {
        $leafs = array();

        $leafs[] = array_map(function ($key) use ($depth, $value) {
            $leaf = makeIndent($depth + 1) .
                "{$key}: " .
                parseValue($value->$key, $depth + 1);
            return $leaf;
        }, array_keys((array) $value));

        $branch = implode("\n", flattenAll($leafs));
        return "{\n" . $branch . "\n" . makeIndent($depth) . "}";
    }
}

function stylish(object $AST): string
{
    $iter = function (object $AST, int $depth) use (&$iter) {

        return array_map(function ($node) use ($iter, $depth) {
            [
                'type' => $type,
                'key' => $key,
                'oldValue' => $oldValue,
                'newValue' => $newValue
            ] = (array) $node;

            $indent = makeIndent($depth - 1);

            switch ($type) {
                case 'added':
                    return "{$indent}  + {$node->key}: " . parseValue($newValue, $depth);
                case 'removed':
                    return "{$indent}  - {$node->key}: " . parseValue($oldValue, $depth);
                case 'unchanged':
                    return "{$indent}    {$key}: " . parseValue($oldValue, $depth);
                case 'changed':
                    $oldLine = "{$indent}  - {$key}: " . parseValue($oldValue, $depth);
                    $newLine = "{$indent}  + {$key}: " . parseValue($newValue, $depth);
                    return "{$oldLine}\n{$newLine}";
                case 'children':
                    return makeIndent($depth) .
                        "{$key}: {\n" .
                         implode("\n", flattenAll($iter($node->oldValue, $depth + 1))) .
                         "\n" . makeIndent($depth) . "}";
                default:
                    throw new \Exception("Type {$type} not supported");
            }
        }, (array) $AST);
    };

    return implode("\n", flattenAll(['{', $iter($AST, 1), '}']));
}
