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
             )
           )*
        )\s*\/>
        /x
        REGEX;

        return preg_replace_callback(
            $regex,
            function ($matches) {
                // dd($matches);
                return sprintf(
                    <<<'HTML'
                        <?php
                        $component_renderer->prepare('components.%s');
                        echo $component_renderer->render();
                        ?>
                    HTML,
                    $matches['name']
                );
            },
            $template
        );

        return $template;
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
