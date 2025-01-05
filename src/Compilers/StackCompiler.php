<?php

namespace Blade\Compilers;

trait StackCompiler
{
    public function compilePush(string $expression): string
    {
        return "<?php \$__env->startPush{$expression}; ?>";
    }

    public function compileEndPush(string $expression): string
    {
        return "<?php \$__env->endPush(); ?>";
    }

    public function compileStack(string $expression): string
    {
        return "<?php echo \$__env->getStack{$expression}; ?>";
    }
}
