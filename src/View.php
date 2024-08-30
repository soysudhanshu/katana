<?php

namespace Blade;

use Blade\Interfaces\HtmlableInterface;

class View implements HtmlableInterface
{
    public function __construct(protected Blade $engine, public string $name, public array $data = []) {}

    public function with(array $data): static
    {
        $this->data = array_merge($this->data, $data);

        return $this;
    }

    public function fragment(string $name)
    {
        (string) $this;
        return $this->engine->getFragment($name);
    }

    public function fragments(array $fragments)
    {
        (string) $this;

        $output = '';

        foreach ($fragments as $name) {
            $output .= $this->engine->getFragment($name);
        }

        return $output;
    }

    public function fragmentIf(bool| callable $condition, string $name)
    {
        (string) $this;

        $value = is_callable($condition) ? $condition() : $condition;

        if ($value) {
            return $this->engine->getFragment($name);
        }

        return $this;
    }

    public function toHtml(): string
    {
        return (string) $this;
    }

    public function __toString(): string
    {
        ob_start();
        $this->engine->renderContents($this->name, $this->data);
        return ob_get_clean();
    }
}
