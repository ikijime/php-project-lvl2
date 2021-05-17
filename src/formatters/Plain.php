<?php

declare(strict_types=1);

namespace Differ\Formatters;

use function Funct\Collection\flattenAll;

function parseVal(mixed $value): string
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if (is_null($value)) {
        return 'null';
    }

    if (is_string($value)) {
        return "'{$value}'";
    }

    if (is_object($value)) {
        return "[complex value]";
    }

    return (string) $value;
}

function plain(object $AST): string
{
    $iter = function (object $AST, string $path) use (&$iter) {

        return array_map(function ($node) use ($iter, $path) {
            [
                'type' => $type,
                'key' => $key,
                'oldValue' => $oldValue,
                'newValue' => $newValue
            ] = (array) $node;

            $path = (strlen($path) > 1) ? implode('.', [$path, $key]) : $key;

            switch ($type) {
                case 'added':
                    return "Property '{$path}' was added with value: " . parseVal($newValue);
                case 'removed':
                    return "Property '{$path}' was removed";
                case 'unchanged':
                    return;
                case 'changed':
                    return "Property '{$path}' was updated. From " . parseVal($oldValue) . " to " . parseVal($newValue);
                case 'children':
                    return $iter($oldValue, $path);
                default:
                    throw new \Exception("Type {$type} not supported");
            }
        }, (array) $AST);
    };

    return implode("\n", array_filter(flattenAll($iter($AST, $path = '1'))));
}
