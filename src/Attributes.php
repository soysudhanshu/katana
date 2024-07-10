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

    public function class(string | array $classes): static
    {
        if (empty($this->attributes['class'])) {
            $this->attributes['class'] = '';
        }

        $classes =  is_string($classes) ? explode(" ", $classes) : $classes;

        $this->attributes['class'] = sprintf(
            "%s %s",
            $this->attributes['class'],
            implode(" ", Blade::getApplicableClasses($classes))
        );

        $this->attributes['class'] = trim($this->attributes['class']);

        return $this;
    }

    public function merge(array $attributes): static
    {
        foreach ($attributes as $key => $value) {

            if (!isset($this->attributes[$key])) {
                $this->attributes[$key] = '';
            } else {
                $value = " $value";
            }

            $this->attributes[$key] .= $value;
        }

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


    public function get(string $key)
    {
        return $this->attributes[toCamelCase($key)] ?? null;
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
        $key = toCamelCase($key);

        return isset($this->attributes[$key]);
    }
}
