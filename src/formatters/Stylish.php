<?php

declare(strict_types=1);

namespace Differ\Formatters;

use function Functional\flatten;

const INDENT_WIDTH = 4;

function makeIndent(int $depth): string
{
    return str_repeat(' ', INDENT_WIDTH * $depth);
}

function parseValue(mixed $value, int $depth): string
{
    if (is_bool($value) || is_null($value)) {
        return json_encode($value, JSON_THROW_ON_ERROR);
    }

    if (is_object($value)) {
        $indent = makeIndent($depth);

        $leafs = array_map(function ($key) use ($value, $depth): string {
            $doubleIndent = makeIndent($depth + 1);
            $nD = $depth + 1;
            return "{$doubleIndent}{$key}: " . parseValue($value->$key, $nD);
        }, array_keys((array) $value));

        $branch = implode("\n", flatten($leafs));
        return "{\n{$branch}\n{$indent}}";
    }

    return (string) $value;
}

function stylish(object $AST): string
{
    $astArray = (array) $AST;
    $iter = function (array $astArray, int $depth) use (&$iter): array {

        return array_map(function ($node) use ($iter, $depth): string {
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
                         implode("\n", flatten($iter((array) $node->oldValue, $depth + 1))) .
                         "\n" . makeIndent($depth) . "}";
                default:
                    throw new \Exception("Type {$type} not supported");
            }
        }, $astArray);
    };

    return implode("\n", flatten(['{', $iter($astArray, 1), '}']));
}
