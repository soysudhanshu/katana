<?php

namespace Blade;

class CompileAtRules
{
    public function __construct(protected string $content)
    {
    }


    public function compile(): string
    {
        $statementRegex = "/@(?'directive'[a-z]+)\s*(?'expression'\(.*?\))?/";

        $this->content = preg_replace_callback($statementRegex, function ($matches) {
            $methodName = sprintf("compile%s", ucfirst($matches['directive']));

            if (method_exists($this, $methodName)) {
                return $this->{$methodName}($matches);
            }
        }, $this->content);

        return $this->content;
    }

    protected function compileIf(array $matches): string
    {
        return "<?php if{$matches['expression']}: ?>";
    }

    protected function compileEndif(array $matches): string
    {
        return "<?php endif; ?>";
    }

    protected function compileElse(array $matches): string
    {
        return "<?php else: ?>";
    }
}
