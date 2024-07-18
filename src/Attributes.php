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
            implode(" ", Blade::filterConditionalValues($classes))
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

        return $this->filter(fn (string $key) => !in_array($key, $attributes));
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
        return $this->attributes[$key] ?? null;
    }

    public function toArray(): array
    {
        return $this->attributes;
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->attributes);
    }

    /**
     * Determine if one or more attributes are set.
     *
     * @param  string|array $key
     * @return bool
     */
    public function has(string|array $keys): bool
    {
        $found = true;

        $keys = is_string($keys) ? [$keys] : $keys;

        foreach ($keys as $key) {
            if (!array_key_exists($key, $this->attributes)) {
                $found = false;
                break;
            }
        }

        return $found;
    }

    /**
     * Determine if any of the given attributes are present.
     *
     * @param array $keys
     * @return boolean
     */
    public function hasAny(array $keys): bool
    {
        $found = false;

        foreach ($keys as $key) {
            if (array_key_exists($key, $this->attributes)) {
                $found = true;
                break;
            }
        }

        return $found;
    }


    public function filter(callable $callback): static
    {
        $attributes = array_filter(
            $this->attributes,
            fn (string $value, string $key) => $callback($key, $value),
            ARRAY_FILTER_USE_BOTH
        );

        return new Attributes($attributes);
    }

    public function whereStartsWith(string $needle): static
    {
        return $this->filter(fn (string $key) => str_starts_with($key, $needle));
    }

    public function whereDoesntStartWith(string $needle): static
    {
        return $this->filter(fn (string $key) => !str_starts_with($key, $needle));
    }

    public function first(): static
    {
        return new static(array_slice($this->attributes, 0, 1, true));
    }
}
