<?php

use Blade\BladeCompiler;
use PHPUnit\Framework\TestCase;


class OutputDirectiveTest extends TestCase
{
    protected BladeCompiler $compiler;

    public function setup(): void
    {
        $this->compiler = new BladeCompiler();
    }

    public function testOutputDirective()
    {
        $this->assertEquals(
            '<?php echo htmlentities($output); ?>',
            $this->compiler->compileString('{{$output}}')
        );
    }

    public function testAllowsExpression()
    {
        $this->assertEquals(
            '<?php echo htmlentities(1 + 2); ?>',
            $this->compiler->compileString('{{1 + 2}}')
        );
    }

    public function testAllowsFunctions()
    {
        $this->assertEquals(
            '<?php echo htmlentities( time() ); ?>',
            $this->compiler->compileString("{{ time() }}")
        );
    }

    public function testUnsafeOutputDirective()
    {
        $this->assertEquals(
            '<?php echo $output; ?>',
            $this->compiler->compileString('{!!$output!!}')
        );
    }

    public function testUnsafeAllowsExpression()
    {
        $this->assertEquals(
            '<?php echo 1 + 2; ?>',
            $this->compiler->compileString('{!!1 + 2!!}')
        );
    }

    public function testUnsafeAllowsFunctions()
    {
        $this->assertEquals(
            '<?php echo time(); ?>',
            $this->compiler->compileString("{!!time()!!}")
        );
    }
}
