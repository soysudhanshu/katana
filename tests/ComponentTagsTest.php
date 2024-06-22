<?php

use Blade\BladeCompiler;
use PHPUnit\Framework\TestCase;

class ComponentTagsTest extends TestCase
{
    protected BladeCompiler $compiler;

    public function setup(): void
    {
        $this->compiler = new BladeCompiler();
    }

    public function testCompilesTagIntoDirective()
    {
        $this->assertEquals(
            "<?php (new Component('components.component-name', []))->render(); ?>",
            $this->compiler->compileString('<x-component-name></x-component-name>')
        );
    }
}
