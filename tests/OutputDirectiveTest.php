<?php

namespace Tests;

use Blade\BladeCompiler;
use PHPUnit\Framework\TestCase;

class OutputDirectiveTest extends TestCase
{
    use VerifiesOutputTrait;

    public function testOutputDirective()
    {
        $this->assertEquals(
            "<div>Hello, World!</div>",
            $this->renderBlade("<div>{{ 'Hello, World!' }}</div>")
        );
    }

    public function testAllowsExpression()
    {
        $this->assertEquals(
            '3',
            $this->renderBlade('{{1 + 2}}')
        );
    }

    public function testUnsafeOutputDirective()
    {
        $this->assertEquals(
            '<script>alert()</script>',
            $this->renderBlade('{!! "<script>alert()</script>" !!}')
        );
    }

    public function testUnsafeAllowsExpression()
    {
        $this->assertEquals(
            '3',
            $this->renderBlade('{!!1 + 2!!}')
        );
    }
}
