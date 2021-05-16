<?php

declare(strict_types=1);

namespace Differ\Formatters;

function json(object $AST): string
{
    return json_encode($AST, JSON_THROW_ON_ERROR);
}
