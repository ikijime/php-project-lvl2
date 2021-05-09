<?php

declare(strict_types=1);

namespace Differ\Differ;

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

    return (string) $value;
}

function genDiff(string $filePath1, string $filePath2, string $format = "stylish"): string
{

    $fileArray1 = parse($filePath1);
    $fileArray2 = parse($filePath2);
    $keys = union(array_keys($fileArray1), array_keys($fileArray2));
    $sortedKeys = sortBy($keys, fn($key) => $key);

    $result = array_map(function ($key) use ($fileArray1, $fileArray2) {

        $value1 = parseValue($fileArray1[$key] ?? null);
        $value2 = parseValue($fileArray2[$key] ?? null);

        if (!array_key_exists($key, $fileArray2)) {
            return "- {$key}: {$value1}";
        }

        if (!array_key_exists($key, $fileArray1)) {
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
