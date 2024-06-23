<?php

namespace Blade\Interfaces;

use Stringable;

interface HtmlableInterface extends Stringable
{
    public function toHtml(): string;
}
