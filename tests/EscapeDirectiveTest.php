<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class EscapeDirectiveTest extends TestCase
{
    use VerifiesOutputTrait;

    public function testAtEscapeDirective(): void
    {
        $this->assertSame(
            '{{ $name }}',
            $this->renderBlade('@{{ $name }}')
        );
    }

    public function testAtEscapeDirectiveWithHtml(): void
    {
        $this->assertSame(
            '<p>{{ $name }}</p>',
            $this->renderBlade('<p>@{{ $name }}</p>')
        );
    }

    public function testEscapesUnescapedOutput(): void
    {
        $this->assertSame(
            '{!! $name !!}',
            $this->renderBlade('@{!! $name !!}')
        );
    }

    public function testAtDirectives(): void
    {
        $directives = [
            '@@if ($name)',
            '@@elseif ($name)',
            '@@switch ($name)',
        ];

        foreach ($directives as $directive) {
            $this->assertSame(
                substr($directive, 1),
                $this->renderBlade($directive)
            );
        }
    }
}
