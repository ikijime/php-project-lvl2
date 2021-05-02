<?php

declare(strict_types=1);

namespace Differ\Differ;

use function Funct\Collection\sortBy;
use function Funct\Collection\union;

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

function getFileContent(string $filePath): string
{
    if (!file_exists($filePath)) {
        throw new \Exception("Invalid filepath: {$filePath}");
    }

    return (string) file_get_contents($filePath);
}

function genDiff(string $filePath1, string $filePath2, string $format = "stylish"): string
{

    $fileArray1 = json_decode(getFileContent($filePath1), true);
    $fileArray2 = json_decode(getFileContent($filePath2), true);
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
