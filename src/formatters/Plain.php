<?php

declare(strict_types=1);

namespace Differ\Formatters;

use function Differ\Differ\array_flatten;

function toString(mixed $value): string
{
    if (is_bool($value) || is_null($value)) {
        return (string) json_encode($value, JSON_THROW_ON_ERROR);
    }

    if (is_object($value)) {
        return "[complex value]";
    }

    if (is_string($value)) {
        return (string) "'{$value}'";
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
                    return "Property '{$path}' was added with value: " . toString($newValue);
                case 'removed':
                    return "Property '{$path}' was removed";
                case 'unchanged':
                    return;
                case 'changed':
                    return "Property '{$path}' was updated. From " . toString($oldValue) . " to " . toString($newValue);
                case 'children':
                    return $iter($oldValue, $path);
                default:
                    throw new \Exception("Type {$type} not supported");
            }
        }, (array) $AST);
    };

    return implode("\n", array_flatten([$iter($AST, '')]));
}
