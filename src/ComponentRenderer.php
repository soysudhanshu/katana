<?php

namespace Blade;

use Blade\Component;

class ComponentRenderer
{
    protected array $stack = [];

    public function __construct(private Blade $blade)
    {
    }

    public function prepare(string $name, $data = [])
    {
        $this->stack[] = [
            'name' => $name,
            'data' => $data,
            'attributes' => new Attributes($data),
        ];
        ob_start();
    }


    public function render()
    {
        $slot = ob_get_clean();

        if (empty($this->stack)) {
            return 'Trying to call render without compoenent being prepared';
        }

        $component = array_pop($this->stack);

        $viewData = [
            'slot' => fn () => $slot,
            ...$component['data'],
            'attributes' => $component['attributes'],
        ];

        return $this->blade->render(
            $component['name'],
            $viewData
        );
    }
}
