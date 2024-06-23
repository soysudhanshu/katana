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

        $matches = [];

        preg_match_all($statementRegex, $this->content, $matches);

        if (empty($matches)) {
            return $this->content;
        }

        foreach ($matches[0] as $directive) {
            /**
             * It is going ro be tricky as
             * we are unable to get correct
             * number of parenthesis using regex.
             *
             * So, we are going to loop and reconstruct
             * the expression.
             *
             * @todo Revisit regex to get correct number of parenthesis
             */
            $openParenthesisCount = substr_count($directive, '(',);
            $closeParenthesisCount = substr_count($directive, ')');

            if ($openParenthesisCount !== $closeParenthesisCount) {
                $start = strpos($this->content, $directive) + strlen($directive);

                do {
                    $nextChar = substr($this->content, $start, 1);

                    if ($nextChar === '(') {
                        $openParenthesisCount++;
                    }

                    if ($nextChar === ')') {
                        $closeParenthesisCount++;
                    }

                    $directive .= $nextChar;

                    $start++;
                } while ($openParenthesisCount !== $closeParenthesisCount);
            }

            $this->content = $this->compileDirective($directive, $this->content);
        }

        return $this->content;
    }

    protected function compileDirective(string $directive, string $content): string
    {
        $directiveName = '';

        if (str_contains($directive, '(')) {
            $directiveName = substr(
                strstr($directive, '(', true),
                offset: 1
            );
        } else {
            $directiveName = substr($directive, offset: 1);
        }

        $directiveName = trim($directiveName);

        $expression = substr(
            $directive,
            offset: strlen($directiveName) + 1,
        );

        if (empty($directiveName)) {
            return $content;
        }

        $methodName = sprintf("compile%s", ucfirst($directiveName));

        if (method_exists($this, $methodName)) {
            $content = $this->replaceDirective(
                $directive,
                $this->{$methodName}($expression),
                $content
            );
        }

        return $content;
    }

    protected function replaceDirective(string $directive, string $replacement, string $content): string
    {
        $before = strstr($content, $directive, before_needle: true);

        return  $before . $replacement . substr($content, strlen($before) + strlen($directive));
    }

    protected function compileIf(string $expression): string
    {
        return "<?php if{$expression}: ?>";
    }

    protected function compileEndif(string $expression): string
    {
        return "<?php endif; ?>";
    }

    protected function compileElse(string $expression): string
    {
        return "<?php else: ?>";
    }

    protected function compileForeach(string $expression): string
    {
        return "<?php foreach{$expression}: ?>";
    }

    protected function compileEndforeach(string $expression): string
    {
        return "<?php endforeach; ?>";
    }
}
