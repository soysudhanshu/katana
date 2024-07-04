<?php

namespace Blade;


class Compiler
{
    /**
     * Stores blocks of code that must
     * be rendered as is. E.g @verbatim
     *
     * @var array
     */
    protected array $contentBlocks = [];

    public function __construct(protected string $template)
    {
    }


    public function compile()
    {
        $result = $this->template;

        $result = $this->compileVerbatimDirective($result);
        $result = $this->compileCommentDirective($result);
        $result = (new ComponentTagsCompiler($result))->compile();
        $result = (new CompileAtRules($result))->compile();
        $result = $this->compileComponentAttributes($result);
        $result = $this->compileOutputDirective($result);
        // $result = $this->compileOutputDirective($result);
        $result = $this->compileUnsafeOutputDirective($result);

        $result = $this->replaceContentBlocks($result);

        return $result;
    }

    protected function compileVerbatimDirective(string $template): string
    {
        return preg_replace_callback(
            "/@verbatim(?'content'(?:\s|.)+)@endverbatim/",
            function (array $matches) {
                $content = $matches['content'];
                $identifier = md5($content);

                $this->contentBlocks[$identifier] = $content;

                return $this->getContentBlockTag($identifier);
            },
            $template
        );
    }

    protected function getContentBlockTag(string $identifier): string
    {
        return sprintf(
            "\n##CONTENT_BLOCK %s ###\n",
            $identifier
        );
    }

    protected function replaceContentBlocks(string $template): string
    {
        foreach ($this->contentBlocks as $identifier => $content) {
            $template = str_replace(
                $this->getContentBlockTag($identifier),
                $content,
                $template,
            );
        }

        return $template;
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
        return preg_replace_callback("/(@|){{\s*(?'expression'(?:\s+|.)*?)\s*}}/", function (array $matches) {
            $directive = $matches[0];
            $expression = $matches['expression'];

            if (str_starts_with($directive, '@')) {
                return substr($directive, 1);
            }

            return "<?php echo \Blade\\e($expression); ?>";
        }, $template);
    }

    protected function compileUnsafeOutputDirective(string $template)
    {
        return preg_replace_callback("/(@|){!!(?'expression'.*?)!!}/",  function (array $matches) {
            $directive = $matches[0];
            $expression = $matches['expression'];

            if (str_starts_with($directive, '@')) {
                return substr($directive, 1);
            }

            return "<?php echo $expression; ?>";
        }, $template);
    }
}
