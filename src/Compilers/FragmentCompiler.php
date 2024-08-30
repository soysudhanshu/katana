<?php

namespace Blade\Compilers;

trait FragmentCompiler
{
    public function compileFragment(string $expression): string
    {
        return "<?php \$__env->startFragment{$expression}; ?>";
    }

    public function compileEndFragment(string $expression): string
    {
        return "<?php \$__env->endFragment(); ?>";
    }
}
