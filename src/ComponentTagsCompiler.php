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
        // dump($result);
        // $result = $this->compileOpeningTags($result);
        // $result = $this->compileClosingTags($result);

        return $result;
    }

    protected function compileSelfClosingTags(string $template): string
    {
        $regex = <<<'REGEX'
        /
        <x-(?'name'[\w]+)
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

                return "<?php \$component_renderer->prepare('components.{$matches['name']}', {$attributes});" .
                    "echo \$component_renderer->render(); ?>";
            },
            $template
        );

        return $template;
    }

    protected function parseAttributes(string $attribute): string
    {
        if ($attribute === '') {
            return '';
        }

        $attributes =   preg_replace_callback(
            "/((?'name'[\w:-]+)(?>=(?'value'\"[^\"]+\"|'[^']+'))?)/",
            function ($matches) {

                $name = $matches['name'];
                $value = $matches['value'] ?? null;

                if (str_starts_with($name, ':')) {
                    $name = substr($name, 1);
                    $value = trim($value, '"');

                    return "'{$name}' => {$value},";
                }

                if (isset($matches['value'])) {
                    return "'{$matches['name']}' => {$matches['value']},";
                }

                return "'{$matches['name']}' => true,";
            },
            $attribute
        );

        return "[ $attributes ]";
    }


    protected function compileOpeningTags(string $template): string
    {

        $regex = "/(?'tag'<x-(?'name'[:a-z-]+)\s+(.*?)>)/";

        return preg_replace(
            $regex,
            <<<'HTML'
            <?php $component_renderer->prepare('components.$1'); ?>
            HTML,
            $template
        );

        return $template;
    }

    protected function compileClosingTags(string $template): string
    {
        $regex = "/<\/x-(?'name'[a-z\.-]+)>/";

        return preg_replace(
            $regex,
            <<<'HTML'
            <?php echo $component_renderer->render(); ?>
            HTML,
            $template
        );

        return $template;
    }
}
