<?php

namespace Blade;

use Blade\Environments\FragmentEnvironment;

final class Blade
{
    public static string $cachePath;
    public static string $viewPath;

    public array $fragments = [];
    public ComponentRenderer $componentRenderer;
    public TemplateInheritanceRenderer $templateRenderer;

    use FragmentEnvironment;

    public static function setCachePath(string $path): void
    {
        self::$cachePath = $path;
    }

    public static function setViewPath(string $path): void
    {
        self::$viewPath = $path;
    }

    public function __construct()
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

    public function render(string $view, array $data = []): View
    {
        return new View($this, $view, $data);
    }

    public function renderContents(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);

        $component_renderer = $this->componentRenderer;
        $template_renderer = $this->templateRenderer;
        $__env = $this;

        include $this->getCachedViewPath($this->compile($view));
    }

    public function viewExists(string $name): bool
    {
        return file_exists($this->getViewPath($name));
    }

    protected function getViewPath(string $name): string
    {
        return sprintf(
            '%s/%s.blade.php',
            rtrim(self::$viewPath, '/'),
            str_replace('.', '/', $name)
        );
    }

    protected function getCachedViewPath(string $identifier): string
    {
        return sprintf(
            '%s/%s.php',
            rtrim(self::$cachePath, '/'),
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
