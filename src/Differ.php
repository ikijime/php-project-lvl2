<?php

declare(strict_types=1);

namespace Differ\Differ;

use function Differ\Parsers\parse;
use function Funct\Collection\union;
use function Funct\Collection\sortBy;

function parseValue(mixed $value): string
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

    print_r($value);
    return (string) $value;
}

function genDiff(string $filePath1, string $filePath2, string $format = "stylish"): string
{

    $firstFile = parse($filePath1);
    $secondFile = parse($filePath2);
    $keys = union(
        array_keys(get_object_vars($firstFile)),
        array_keys(get_object_vars($secondFile))
    );
    $sortedKeys = sortBy($keys, fn($key) => $key);

    $result = array_map(function ($key) use ($firstFile, $secondFile) {

        $value1 = parseValue($firstFile->$key ?? null);
        $value2 = parseValue($secondFile->$key ?? null);

        if (!property_exists($secondFile, (string) $key)) {
            return "- {$key}: {$value1}";
        }

        if (!property_exists($firstFile, (string) $key)) {
            return "+ {$key}: {$value2}";
        }

        if ($value1 === $value2) {
            return "  {$key}: {$value1}";
        }

        if ($value1 !== $value2) {
            $oldValue = "- {$key}: {$value1}";
            $newValue = "+ {$key}: {$value2}";
            return $oldValue . "\n  " . $newValue;
        }

        return;
    }, $sortedKeys);
    return "{\n  " . implode("\n  ", $result) . "\n}";
}
