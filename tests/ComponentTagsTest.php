<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class ComponentTagsTest extends TestCase
{
    use VerifiesOutputTrait;

    public function testSelfClosingTagOutput()
    {
        $this->createComponent('alert', '<div>Hello, World!</div>');

        $this->assertSame(
            "<div>Hello, World!</div>",
            $this->renderBlade("<x-alert />")
        );
    }

    public function testSelfClosingComponentWithoutSpace()
    {
        $this->createComponent('alert', '<div>Hello, World!</div>');

        $this->assertSame(
            "<div>Hello, World!</div>",
            $this->renderBlade("<x-alert/>")
        );
    }

    public function testWithBooleanAttribute()
    {
        $this->createComponent('alert', '<div>Hello, World! {{ $show }}</div>');

        $this->assertSame(
            "<div>Hello, World! 1</div>",
            $this->renderBlade('<x-alert show />')
        );
    }
}
