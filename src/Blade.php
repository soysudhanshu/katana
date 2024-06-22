<?php

namespace Blade;

final class Blade
{
    public function __construct(protected string $viewPath, protected string $cachePath)
    {
    }


    public function compile(string $view): string
    {
        $path = $this->getViewPath($view);

        if (!file_exists($path)) {
            throw new \Exception('View not found' .  $path);
        }

        $identifier = hash('sha256', $path);

        $this->saveCache(
            $identifier,
            (new Compiler(file_get_contents($path)))->compile()
        );

        return $identifier;
    }

    public function render(string $view, array $data = []): void
    {
        extract($data);

        $component_renderer = new ComponentRenderer($this);

        include $this->getCachedViewPath($this->compile($view));
    }

    protected function getViewPath(string $name): string
    {
        return sprintf(
            '%s/%s.blade.php',
            rtrim($this->viewPath, '/'),
            str_replace('.', '/', $name)
        );
    }

    protected function getCachedViewPath(string $identifier): string
    {
        return sprintf(
            '%s/%s.php',
            rtrim($this->cachePath, '/'),
            $identifier
        );
    }

    private function saveCache(string $identifier, string $compiled): void
    {
        file_put_contents(
            $this->getCachedViewPath($identifier),
            $compiled
        );
    }
}
