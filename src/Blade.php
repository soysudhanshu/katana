<?php

namespace Blade;

final class Blade
{
    private array $viewPaths = [
        __DIR__ . '/../views',
    ];

    public ComponentRenderer $componentRenderer;

    public function __construct(string $viewPath, protected string $cachePath)
    {
        $this->viewPaths[] = $viewPath;
        $this->componentRenderer = new ComponentRenderer($this);
    }


    public function compile(string $view): string
    {
        $path = $this->getViewPath($view);

        if (!file_exists($path)) {
            throw new \Exception('View not found' .  $path);
        }

        $identifier = hash('sha256', $path);

        $complied = (new Compiler(file_get_contents($path)))->compile();
        $complied .= "<?php ##PATH $path ## ?>";

        $this->saveCache(
            $identifier,
            $complied
        );

        return $identifier;
    }

    public function evaluate(string $view, array $data = []): void
    {
        extract($data);

        $component_renderer = $this->componentRenderer;


        include $this->getCachedViewPath($this->compile($view));;
    }

    public function render(string $view, array $data = []): string
    {
        ob_start();

        $this->evaluate($view, $data);
        
        echo ob_get_clean();
        return  '';
    }

    protected function getViewPath(string $name): string
    {
        foreach ($this->getViewPaths($name) as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }
    }

    protected function getViewPaths(string $name): array
    {
        return array_map(function ($path) use ($name) {
            return sprintf(
                '%s/%s.blade.php',
                $path,
                str_replace('.', '/', $name)
            );
        }, $this->viewPaths);
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

    /**
     * Filters conditional classes and
     * returns only the applicable classes.
     *
     * @param array $classes
     * @return array
     */
    public static function getApplicableClasses(array $classes): array
    {
        $applicable = [];

        foreach ($classes as $key => $value) {
            if (is_int($key)) {
                $applicable[] = $value;
            } else {
                if ($value) {
                    $applicable[] = $key;
                }
            }
        }

        return  $applicable;
    }

    /**
     * Compiles the @class directive.
     *
     * @param array $classes
     * @return string
     */
    public static function classAttribute(array $classes): string
    {
        return sprintf(
            'class="%s"',
            implode(' ', static::getApplicableClasses($classes))
        );
    }

    public function getBladeFileFromCache(string $template): string
    {
        $matches = [];

        preg_match('/##PATH (.*?) ##/', $template, $matches);

        return $matches[1];
    }
}
