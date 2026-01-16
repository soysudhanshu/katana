<?php

namespace Blade;

use Blade\Environments\FragmentEnvironment;
use Blade\Environments\StackEnvironment;

final class Blade
{
    /**
     * Default mode, templates files
     * will be compiled and cached.
     */
    public const MODE_PRODUCTION = 1;

    /**
     * Only to be used in while running
     * tests.
     */
    public const MODE_TESTING = 2;

    public array $fragments = [];
    public ComponentRenderer $componentRenderer;
    public TemplateInheritanceRenderer $templateRenderer;

    public readonly string $viewPath;
    public readonly string $cachePath;

    protected int $mode = self::MODE_PRODUCTION;
    protected array $anonymousComponentPaths = [];

    use FragmentEnvironment;
    use StackEnvironment;

    public function setMode(int $mode): void
    {
        $this->mode = $mode;
    }

    public function __construct(string $viewPath, string $cachePath)
    {
        $this->viewPath = rtrim($viewPath, '/');
        $this->cachePath = rtrim($cachePath, '/');

        /**
         * Add default directory for anonymous components.
         */
        $this->addAnonymousComponentPath(
            sprintf('%s/components', $this->viewPath)
        );

        $this->componentRenderer = new ComponentRenderer($this);
        $this->templateRenderer = new TemplateInheritanceRenderer($this);
    }

    /**
     * Register path for anonymous components other than
     * the default view path.
     */
    public function addAnonymousComponentPath(string $path): self
    {
        $this->anonymousComponentPaths[] = $path;

        return $this;
    }

    public function resolveComponentPath(string $name): string
    {
        foreach ($this->anonymousComponentPaths as $basePath) {
            $path = sprintf("%s/%s", $basePath, $this->getViewFileName($name));

            if (file_exists($path)) {
                return $path;
            }
        }

        return '';
    }

    public function compile(string $viewPath): string
    {
        $path = $viewPath;

        if (!file_exists($path)) {
            throw new \Exception('View not found' .  $path);
        }

        $identifier = $this->getViewIdentifier($path);
        $compiledPath = $this->getCachedViewPath($identifier);

        if (file_exists($compiledPath)) {
            return $identifier;
        }

        $viewContents = file_get_contents($path);

        $complied = (new Compiler($viewContents))->compile();
        $complied .= "<?php ##PATH $path ## ?>";

        $this->saveCache($identifier, $complied);

        return $identifier;
    }

    public function getViewIdentifier(string $path): string
    {
        /**
         * During unit tests the resolution time of filemtime
         * might not be sufficient, to identify changes
         * in the file to trigger recompilation.
         */
        if ($this->mode === self::MODE_TESTING) {
            return hash('xxh64', file_get_contents($path));
        }

        return hash('xxh64', $path . filemtime($path));
    }

    public function render(string $view, array $data = []): View
    {
        return $this->renderViewFile($this->getViewPath($view), $data);
    }

    public function renderViewFile(string $viewPath, array $data): View
    {
        return new View($this, $viewPath, $data);
    }

    public function renderContents(string $viewPath, array $data = []): void
    {
        extract($data, EXTR_SKIP);

        $component_renderer = $this->componentRenderer;
        $template_renderer = $this->templateRenderer;
        $__env = $this;

        include $this->getCachedViewPath($this->compile($viewPath));
    }

    public function viewExists(string $name): bool
    {
        return file_exists($this->getViewPath($name));
    }

    protected function getViewPath(string $name): string
    {
        return sprintf(
            '%s/%s',
            rtrim($this->viewPath, '/'),
            $this->getViewFileName($name)
        );
    }

    protected function getViewFileName(string $name): string
    {
        return sprintf(
            '%s.blade.php',
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
