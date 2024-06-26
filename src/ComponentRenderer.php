<?php

namespace Blade;

use Blade\Component;
use Blade\Interfaces\HtmlableInterface;

class ComponentRenderer
{
    protected array $stack = [];

    public function __construct(private Blade $blade)
    {
    }

    public function prepare(string $name, $data = [])
    {
        $this->stack[] = (object) [
            'name' => $name,
            'data' => $data,
            'attributes' => new Attributes($data),
            'props' => [],
        ];
        ob_start();
    }


    public function render()
    {
        $slot = ob_get_clean();

        if (empty($this->stack)) {
            return 'Trying to call render without compoenent being prepared';
        }

        $component = $this->getLastComponent();

        $component->slot = new class($slot) implements HtmlableInterface
        {
            public function __construct(private string $slot)
            {
            }
            public function toHtml(): string
            {
                return $this->slot;
            }

            public function __toString(): string
            {
                return $this->toHtml();
            }
        };

        $rendered = $this->blade->render(
            $component->name,
            (array) $this->getViewData(),
        );

        return $rendered;
    }

    public function getViewData(): array
    {
        $component = $this->getLastComponent();
        $attributes = $component->attributes;

        $data = [];


        foreach ($attributes as $key => $value) {
            $data[$key] = $value;
        }

        /**
         * Setup remaining props and remove
         * them from the attributes array.
         */
        foreach ($component->props as $key => $prop) {
            if (is_int($key)) {
                $key = $prop;
                $prop = null;
            }

            if (!isset($data[$key])) {
                $data[$key] = $prop;
            }

            if ($attributes->has($key)) {
                $attributes->except($key);
            }
        }


        $data['attributes'] = $attributes;
        $data['slot'] = $component->slot;
        $data['component_renderer'] = $this;

        return $data;
    }

    public function setProps(array $props): void
    {
        $component = $this->getLastComponent();

        $component->props = $props;
    }


    public function getLastComponent(): object
    {
        return $this->stack[count($this->stack) - 1];
    }

    public function popComponent(): void
    {
        array_pop($this->stack);
    }
}
