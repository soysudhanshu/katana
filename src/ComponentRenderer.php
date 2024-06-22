<?php

namespace Blade;

use Blade\Component;

class ComponentRenderer
{
    protected array $stack = [];

    public function __construct(private Blade $blade)
    {
    }

    public function prepare(string $name)
    {
        $this->stack[] = $name;
        ob_start();
    }


    public function render()
    {
        $slot = ob_get_clean();

        if (empty($this->stack)) {
            return 'Trying to call render without compoenent being prepared';
        }

        return $this->blade->render(
            array_pop($this->stack) ?? '',
            ['slot' => fn () => $slot]
        );;
    }
}
