<?php

namespace Blade;

use Blade\Interfaces\HtmlableInterface;
use Stringable;

function e($value): string
{
    if ($value === null) {
        return '';
    }

    if ($value instanceof HtmlableInterface) {
        return $value->toHtml();
    }

    if (is_scalar($value) || $value instanceof Stringable) {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false);
    }

    return sprintf("Cannot convert value of type `%s` to string.", gettype($value));
}


function toKababCase(string $value): string
{
    return strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $value));
}


function toCamelCase(string $value): string
{
    return lcfirst(str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $value))));
}
