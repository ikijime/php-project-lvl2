<?php

namespace Differ\Differ;

use Symfony\Component\Yaml\Yaml;

function parse(string $filePath): object
{
    if (!file_exists($filePath)) {
        throw new \Exception("Invalid filepath: {$filePath}");
    }

    $extension = pathinfo($filePath)['extension'];
    $fileContent = (string) file_get_contents($filePath);

    switch ($extension) {
        case "json":
            return json_decode($fileContent, false, 512, JSON_THROW_ON_ERROR);
        case "yml":
        case "yaml":
            return Yaml::parse($fileContent, Yaml::PARSE_OBJECT_FOR_MAP);
        default:
            throw new \Exception("Format {$extension} not supported.");
    }
}
