<?php

namespace Blade;

trait CompileForeachTrait
{

    protected function compileForeach(string $expression): string
    {
        return "<?php \$loop = new \Blade\Loop(); ?>" .
            "<?php foreach{$expression}: ?>";
    }

    protected function compileEndforeach(string $expression): string
    {
        return "<?php \$loop->increment(); ?>" .
            "<?php endforeach; ?>";
    }
}
