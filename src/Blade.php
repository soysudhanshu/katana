<?php

namespace Blade;

final class Blade
{
    public ComponentRenderer $componentRenderer;
    public TemplateInheritanceRenderer $templateRenderer;

    public function __construct(protected string $viewPath, protected string $cachePath)
    {
        $this->componentRenderer = new ComponentRenderer($this);
        $this->templateRenderer = new TemplateInheritanceRenderer($this);
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

    public function render(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);

        $component_renderer = $this->componentRenderer;
        $template_renderer = $this->templateRenderer;

        include $this->getCachedViewPath($this->compile($view));;
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

    /**
     * Filters conditional values.
     *
     * @param string[] $values
     * @return string[]
     */
    public static function filterConditionalValues(array $values): array
    {
        $applicable = [];

        foreach ($values as $key => $value) {
            if (is_int($key)) {
                $applicable[] = $value;
                continue;
            }

            if ($value) {
                $applicable[] = $key;
            }
        }

        return $applicable;
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
            implode(' ', static::filterConditionalValues($classes))
        );
    }

    public static function styleAttribute(array $styles): string
    {
        $styles = static::filterConditionalValues($styles);

        $styles = array_map(function ($style) {
            return rtrim($style, ';') . ';';
        }, $styles);

        return sprintf(
            'style="%s"',
            implode(' ', static::filterConditionalValues($styles))
        );
    }
}
