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

    public function testComponentNameWithHypen()
    {
        $this->createComponent('alert-info', '<div>Hello, World! {{ $name }}</div>');

        $this->assertSame(
            "<div>Hello, World! Taylor</div>",
            $this->renderBlade('<x-alert-info name="Taylor" />')
        );
    }

    public function testComponentNameWithDirectoryDot()
    {
        $this->createComponent('alerts.info', '<div>Hello, World! {{ $name }}</div>');

        $this->assertSame(
            "<div>Hello, World! Taylor</div>",
            $this->renderBlade('<x-alerts.info name="Taylor" />')
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

    public function testWithAttribute()
    {
        $this->createComponent('alert', '<div>Hello, World! {{ $name }}</div>');

        $this->assertSame(
            "<div>Hello, World! Taylor</div>",
            $this->renderBlade('<x-alert name="Taylor" />')
        );
    }

    public function testWithSnakeCaseAttribute()
    {
        $this->createComponent(
            'alert',
            '<div>Hello, World! {{ $firstName }} {{ $lastName }}</div>'
        );

        $this->assertSame(
            "<div>Hello, World! Maria Jose</div>",
            $this->renderBlade('<x-alert first-name="Maria" last-name="Jose" />')
        );
    }

    public function testAttributeOutput(): void
    {
        $this->createComponent(
            'alert',
            '<div {{ $attributes }}>Hello, World! {{ $name }}</div>'
        );

        $this->assertSame(
            "<div name='Taylor'>Hello, World! Taylor</div>",
            $this->renderBlade('<x-alert name="Taylor" />')
        );
    }

    public function testEscapesAttributeOutput(): void
    {
        $this->createComponent(
            'alert',
            '<div {{ $attributes }}>Hello, World!</div>'
        );

        $this->assertSame(
            "<div name='&lt;script&gt;alert(&#039;Hello, World!&#039;)&lt;/script&gt;'>Hello, World!</div>",
            $this->renderBlade('<x-alert name="<script>alert(\'Hello, World!\')</script>" />')
        );
    }
}
