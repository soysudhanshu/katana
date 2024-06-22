<?php

class Component
{

    public function __construct(private string $name, private array $data = [])
    {
    }

    public function render(): void
    {
        $component_renderer = new ComponentRenderer($this);

        $component_renderer->start();
        include $this->getViewPath();
        echo $component_renderer->render();
    }

    protected function getViewPath(): string
    {
        return sprintf(
            '%s/%s.blade.php',
            rtrim($this->viewPath, '/'),
            str_replace('.', '/', $this->name)
        );
    }
}
