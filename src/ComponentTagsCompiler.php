<?php

namespace Blade;

class ComponentTagsCompiler
{
    public function __construct(protected string $template)
    {
    }

    public function compile()
    {
        $result = $this->compileSelfClosingTags($this->template);
        $result = $this->compileOpeningTags($result);
        $result = $this->compileClosingTags($result);

        return $result;
    }

    protected function compileSelfClosingTags(string $template): string
    {
        $regex = <<<'REGEX'
        /
        <x-(?'name'[\w\.-]+)
        (?'attribute'
            (\s*
                [\w:-]+
                (=
                    (
                        "[^"]+"
                    )
                )*
            )*
        )\s*\/>
        /x
        REGEX;

        return preg_replace_callback(
            $regex,
            function ($matches) {
                $attributes = $this->parseAttributes($matches['attribute']);
                return $this->getStartRenderingCode($matches['name'], $attributes) .
                    $this->getEndRenderingCode();
            },
            $template
        );

        return $template;
    }

    private function getStartRenderingCode(string $componentName, string $attributes): string
    {
        return "<?php \$component_renderer->prepare('components.{$componentName}', {$attributes});?>";
    }

    private function getEndRenderingCode(): string
    {
        return "<?php echo \$component_renderer->render(); \$component_renderer->popComponent(); ?>";
    }

    protected function parseAttributes(string $attribute): string
    {
        if ($attribute === '') {
            return '';
        }

        $attributes =   preg_replace_callback(
            "/((?'name'[\w:-]+)(?>=(?'value'\"[^\"]+\"|'[^']+'))?)/",
            function ($matches) {

                $name = $this->toCamelCase($matches['name']);
                $value = $matches['value'] ?? null;

                if (str_starts_with($name, ':')) {
                    $name = substr($name, 1);
                    $value = trim($value, '"');

                    return "'{$name}' => {$value},";
                }

                if (isset($matches['value'])) {
                    return "'{$name}' => {$matches['value']},";
                }

                return "'{$name}' => true,";
            },
            $attribute
        );

        return "[ $attributes ]";
    }

    private function toCamelCase(string $value): string
    {
        return preg_replace_callback(
            '/(-)([a-z])/',
            fn ($matches) => strtoupper($matches[2]),
            $value
        );
    }


    protected function compileOpeningTags(string $template): string
    {

        $regex = "/<x-(?'name'[\w\-\.]+)>/";

        return preg_replace_callback(
            $regex,
            function ($matches) {
                return $this->getStartRenderingCode($matches['name'], '[]');
            },
            $template
        );
    }

    protected function compileClosingTags(string $template): string
    {
        $regex = "/<\/x-(?'name'[a-z\.-]+)>/";

        return preg_replace(
            $regex,
            $this->getEndRenderingCode(),
            $template
        );

        return $template;
    }
}
