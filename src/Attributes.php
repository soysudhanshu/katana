<?php

namespace Blade;

use Blade\Interfaces\HtmlableInterface;
use IteratorAggregate;
use Stringable;
use Traversable;

class Attributes implements HtmlableInterface, IteratorAggregate
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

    public function merge(array $attributes): static
    {
        $this->attributes = array_merge($this->attributes, $attributes);

        return $this;
    }

    /**
     * Prevents attributes from being rendered.
     *
     * @return static
     */
    public function except(string|array $attributes): static
    {
        $attributes = is_string($attributes) ? [$attributes] : $attributes;

        foreach ($attributes as $attribute) {
            unset($this->attributes[$attribute]);
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
            if (!is_scalar($value) && !($value instanceof Stringable)) {
                continue;
            }

            $value = htmlentities($value, ENT_QUOTES, 'UTF-8', false);

            $output[] = "{$key}='{$value}'";
        }

        return implode(" ", $output);
    }

    public function toArray(): array
    {
        return $this->attributes;
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->attributes);
    }

    public function has(string $key): bool
    {
        return isset($this->attributes[$key]);
    }
}
