<?php

namespace Blade;


class Compiler
{
    public function __construct(protected string $template)
    {
    }


    public function compile()
    {
        $result = $this->template;

        $result = $this->compileCommentDirective($result);
        $result = (new ComponentTagsCompiler($result))->compile();
        $result = (new CompileAtRules($result))->compile();
        $result = $this->compileComponentAttributes($result);
        $result = $this->compileOutputDirective($result);
        // $result = $this->compileOutputDirective($result);
        $result = $this->compileUnsafeOutputDirective($result);


        return $result;
    }

    protected function compileComponentAttributes(string $template): string
    {
        return $template;
    }


    protected function compileComponentTags(string $template): string
    {
        $regex = "/<x-(?'name'[a-z\.-]+)>/";

        $stack = [];

        do {
            $template = preg_replace_callback(
                $regex,
                function ($matches) {
                    $name = "components." . $matches['name'];

                    $this->blade->compile($name);

                    return "<?php (new Blade\Component('$name'))->render(); ?>";
                },
                $template
            );
        } while (preg_match($regex, $template));

        return $template;
    }

    protected function compileCommentDirective(string $template)
    {
        return preg_replace("/{{--([\s]*?(.|\s)*?[\s]*?)--}}/", '', $template);
    }


    protected function compileOutputDirective(string $template)
    {
        return preg_replace("/{{\s*((\s+|.)*?)\s*}}/", '<?php echo \Blade\e(${1}); ?>', $template);
    }

    protected function compileUnsafeOutputDirective(string $template)
    {
        return preg_replace("/{!!(.*?)!!}/", '<?php echo ${1}; ?>', $template);
    }
}
