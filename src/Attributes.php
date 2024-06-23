<?php

namespace Blade;

use Blade\Interfaces\HtmlableInterface;

class Attributes implements HtmlableInterface
{
    public function __construct(protected array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function class($classes): static
    {
        if (!isset($this->attributes['class'])) {
            $this->attributes['class'] = '';
        }

        if (is_array($classes)) {
            $this->attributes['class'] = implode(' ', $classes);
        }

        if (is_string($classes)) {
            $this->attributes['class'] .= " $classes";
        }

        return $this;
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

    public function toArray(): array
    {
        return $this->attributes;
    }
}
