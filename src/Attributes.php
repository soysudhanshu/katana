<?php

namespace Blade;

use Blade\Interfaces\HtmlableInterface;

class Attributes implements HtmlableInterface
{
    public function __construct(protected array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function __toString(): string
    {
        return $this->toHtml();
    }

    public function toHtml(): string
    {
        $output = [];

        foreach ($this->attributes as $key => $value) {
            $value = htmlentities($value, ENT_QUOTES, 'UTF-8', false);
            
            $output[] = "{$key}='{$value}'";
        }

        return implode(" ", $output);
    }
}
