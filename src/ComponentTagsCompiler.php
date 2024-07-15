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
        /<x-(?'name'[a-z0-9-.]*)
        \s*
        (?'attributes'
           (?>[\w\:\-]+
           (?:=
             (?>
               "[^"]+" |
               '[^']+' |
               [\w\$]+
             )\s*|
           )\s*
          )*
        )\s*(\/>)
        /x
        REGEX;

        return preg_replace_callback(
            $regex,
            function ($matches) {
                $attributes = $this->parseAttributes($matches['attributes']);
                return $this->getStartRenderingCode($matches['name'], $attributes) .
                    $this->getEndRenderingCode();
            },
            $template
        );

        return $template;
    }

    private function getStartRenderingCode(string $componentName, string $attributes): string
    {
        if ($componentName === 'slot') {
            return  "<?php \$component_renderer->beginSlot('$componentName', {$attributes});?>";
        }

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
            <<<REGEX
            /(
              (?'name'[\w\:\-]+)
               (=
               (?'value'
                 (?>
                   "[^"]+" |
                   '[^']+' |
                   [\w\$]+
                 )
               )
              )?
            )/x
            REGEX,
            function ($matches) {
                $name = $this->toCamelCase($matches['name']);
                $value = $matches['value'] ?? null;

                /**
                 * Check if the value is using an output expression
                 * `{{ ..... }}`. If it is, we need to convert it to
                 * concatinated string.
                 */
                if ($value && preg_match(Compiler::REGEX_OUTPUT_DIRECTIVE, $value) === 1) {

                    $quote = substr($value, 0, 1);

                    $value = preg_replace(
                        Compiler::REGEX_OUTPUT_DIRECTIVE,
                        $quote . " . $2 . " . $quote,
                        $value
                    );
                }


                if ($this->isExpressionAttribute($name)) {
                    $name = substr($name, 1);
                    $value = trim($value, '"');
                    $value = $this->trimQuotes($value);

                    return "'{$name}' => {$value},";
                }

                if ($value) {
                    return "'{$name}' => {$value},";
                }

                return "'{$name}' => true,";
            },
            $attribute
        );

        return "[ $attributes ]";
    }

    /**
     * Check if the attribute value is an expression
     *
     * @param string $name
     * @return bool
     */
    private function isExpressionAttribute(string $name): bool
    {
        return str_starts_with($name, ':');
    }

    private function trimQuotes(string $value): string
    {
        if (str_starts_with($value, '\'') && str_starts_with($value, '\'')) {
            return trim($value, '\'');
        }

        return $value;
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

        $regex = <<<'REGEX'
        /<x-(?'name'[a-z0-9-.]*)
        \s*
        (?'attributes'
           (?>[\w\:\-]+
           (?:=
             (?>
               "[^"]+" |
               '[^']+' |
               [\w\$]+
             )\s*|
           \s*)
          )*
        )\s*(>)
        /x
        REGEX;

        return preg_replace_callback(
            $regex,
            function ($matches) {

                return $this->getStartRenderingCode(
                    $matches['name'],
                    !empty($matches['attributes']) ? $this->parseAttributes($matches['attributes']) : '[]'
                );
            },
            $template
        );
    }

    protected function compileClosingTags(string $template): string
    {
        $regex = "/<\/x-(?'name'[a-z\.-]+)>/";

        return preg_replace_callback(
            $regex,
            function (array $matches) {
                if ($matches['name'] === 'slot') {
                    return "<?php \$component_renderer->endSlot(); ?>";
                }

                return $this->getEndRenderingCode();
            },
            $template
        );

        return $template;
    }
}
