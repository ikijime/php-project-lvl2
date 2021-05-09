<?php

declare(strict_types=1);

namespace Differ\Differ;

use Exception;
use Symfony\Component\Yaml\Yaml;

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

function getFileData(string $filePath): mixed
{
    if (!file_exists($filePath)) {
        throw new \Exception("Invalid filepath: {$filePath}");
    }

    $extension = pathinfo($filePath)['extension'];
    $fileContent = file_get_contents($filePath);

    switch ($extension) {
        case "json":
            return json_decode($fileContent, true);
        case "yml":
            return Yaml::parse($fileContent);
        default:
            throw new \Exception("Format {$extension} not supported.");
    }
}


function genDiff(string $filePath1, string $filePath2, string $format = "stylish"): string
{

    $fileArray1 = getFileData($filePath1);
    $fileArray2 = getFileData($filePath2);
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
