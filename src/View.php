<?php

namespace Blade;

use Blade\Interfaces\HtmlableInterface;

class View implements HtmlableInterface
{
    protected Blade $engine;
    protected bool $rendered;

    public function __construct(public string $name, public array $data = [])
    {
        $this->engine = new Blade;
    }

    public function with(array $data): static
    {
        $this->data = array_merge($this->data, $data);

        return $this;
    }

    public function fragment(string $name)
    {
        (string) $this;
        return $this->engine->fragments[$name]->content;
    }

    public function fragments(array $fragments)
    {
        (string) $this;

        $output = '';

        foreach ($fragments as $name) {
            $output .= $this->engine->fragments[$name]->content;
        }

        return $output;
    }

    public function fragmentIf(bool| callable $condition, string $name)
    {
        (string) $this;

        $value = is_callable($condition) ? $condition() : $condition;

        if ($value) {
            return $this->engine->fragments[$name]->content;
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
        $this->engine->render($this->name, $this->data);
        return ob_get_clean();
    }
}
