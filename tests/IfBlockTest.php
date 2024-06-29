<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class IfBlockTest extends TestCase
{
    use VerifiesOutputTrait;

    public function test_basic_if()
    {
        $this->assertEquals(
            trim($this->renderBlade("@if(true) Hello @endif")),
            "Hello"
        );

        $this->assertEmpty(
            $this->renderBlade("@if(false) Hello @endif"),
        );

        $this->assertEquals(
            trim($this->renderBlade("@if(true) Hello @else Goodbye @endif")),
            "Hello"
        );

        $this->assertEquals(
            trim($this->renderBlade("@if(false) Hello @else Goodbye @endif")),
            "Goodbye"
        );
    }

    public function testSupportsMultipleParenthesis(): void
    {
        $this->assertEquals(
            trim($this->renderBlade("@if((true)) Hello @endif")),
            "Hello"
        );
    }


    public function testSupportsPhp(): void
    {
        $this->assertEquals(
            trim($this->renderBlade("@php echo 'Hello'; @endphp")),
            "Hello"
        );
    }

    public function testElseIfSupport(): void
    {
        $this->assertEquals(
            trim($this->renderBlade("@if(false) Hello @elseif(true) Goodbye @endif")),
            "Goodbye"
        );

        $this->assertEquals(
            trim($this->renderBlade("@if(false) Hello @elseif(false) Goodbye @endif")),
            ""
        );

        $this->assertEquals(
            trim($this->renderBlade("@if(false) Hello @elseif(false) Goodbye @else Hi @endif")),
            "Hi"
        );
    }
}
