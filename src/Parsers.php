<?php

namespace Differ\Differ;

use Symfony\Component\Yaml\Yaml;

function parse(string $filePath): mixed
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
        case "yaml":
            $result = Yaml::parse($fileContent, Yaml::PARSE_OBJECT_FOR_MAP);
            return (array) $result;
        default:
            throw new \Exception("Format {$extension} not supported.");
    }
}
