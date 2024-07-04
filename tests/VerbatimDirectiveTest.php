<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class VerbatimDirectiveTest extends TestCase
{
    use VerifiesOutputTrait;

    public function testDirective(): void
    {
        $content = <<<CONTENT
        {{-- Content inside this block --}}
        {{-- Should be rendered as is  --}}
        CONTENT;

        $blade = <<<BLADE
        @verbatim
        $content
        @endverbatim
        BLADE;

        $this->assertSame(
            $content,
            trim($this->renderBlade($blade))
        );
    }

    public function testNestedVerbatim(): void
    {
        $content = <<<'CONTENT'
        @verbatim
            @if($content)
                {{ $content }}
            @endif
        @endverbatim
        CONTENT;

        $blade = <<<BLADE
        @verbatim
            $content
        @endverbatim
        BLADE;

        $this->assertSame(
            $content,
            trim($this->renderBlade($blade))
        );
    }
}
