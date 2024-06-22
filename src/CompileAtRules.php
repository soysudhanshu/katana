<?php

namespace Blade;

class CompileAtRules
{
    protected array $directives = [
        "if",
        "endif",
    ];

    public function __construct(protected string $content)
    {
    }


    public function compile(): string
    {

        $this->content = $this->compileIf($this->content);

        return $this->content;
    }

    protected function compileIf(string $content): string
    {
        $pattern = '/@if\s*\((.*?)\)/';

        $content = preg_replace($pattern, "<?php if($1): ?>", $content);
        $content = str_replace("@else", "<?php else: ?>", $content);
        $content = str_replace("@endif", "<?php endif; ?>", $content);

        return $content;
    }
}
