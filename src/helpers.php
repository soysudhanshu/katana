<?php

namespace Blade;

use Blade\Interfaces\HtmlableInterface;

function e($value): string
{
    if ($value === null) {
        return '';
    }

    if ($value instanceof HtmlableInterface) {
        return $value->toHtml();
    }

    if (is_scalar($value)) {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false);
    }
}


function toKababCase(string $value): string
{
    return strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $value));
}


function toCamelCase(string $value): string
{
    return lcfirst(str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $value))));
}
