<?php

namespace Blade;

use Blade\Interfaces\HtmlableInterface;

function e($value): string
{
    if($value instanceof HtmlableInterface) {
        return $value->toHtml();
    }

    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false);
}
