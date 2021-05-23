<?php

namespace Differ\Differ;

function array_union(array $first, array $second): array
{
    $merged = array_merge($first, array_diff($second, $first));
    sort($merged);
    return $merged;
}

function array_flatten(array $array): array
{
    $reducedArrays = array_reduce(
        $array,
        function ($prev, $element) {
            if (!is_array($element)) {
                $prev[] = $element;
            } else {
                $prev = array_merge($prev, array_flatten($element));
            }
            return $prev;
        },
        []
    );
    return array_filter($reducedArrays);
}
