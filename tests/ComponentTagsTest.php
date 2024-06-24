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

    public function testMergeAttributes(): void
    {
        $this->createComponent(
            'alert',
            '<div {{ $attributes->merge([ "hello" => "kitty" ]) }}>Hello, World!</div>'
        );

        $this->assertSame(
            "<div class='alert' name='Taylor' hello='kitty'>Hello, World!</div>",
            $this->renderBlade('<x-alert class="alert" name="Taylor" />')
        );
    }

    public function testExceptAttribute(): void
    {
        $this->createComponent(
            'alert',
            '<div {{ $attributes->except("name") }}>Hello, World!</div>'
        );

        $this->assertSame(
            "<div class='alert'>Hello, World!</div>",
            $this->renderBlade('<x-alert class="alert" name="Taylor"/>')
        );
    }

    public function testExceptAttributeWithArray(): void
    {
        $this->createComponent(
            'alert',
            '<div{{ $attributes->except(["name", "class"]) }}>Hello, World! {{ $name }}</div>'
        );

        $this->assertSame(
            "<div>Hello, World! Taylor</div>",
            $this->renderBlade('<x-alert class="alert" name="Taylor"/>')
        );
    }

    public function testPropsSetDefaults(): void
    {
        $this->createComponent(
            'alert',
            '@props(["name" => "Taylor"])' .
                '<div>Hello, World! {{ $name }}</div>'
        );

        $this->assertSame(
            "<div>Hello, World! Taylor</div>",
            $this->renderBlade('<x-alert />')
        );
    }

    public function testPropsIndexedKeySetsValueToNull(): void
    {
        $this->createComponent(
            'alert',
            '@props(["name"])@if(is_null($name)){{ "I am null" }}@endif'
        );

        $this->assertSame(
            "I am null",
            $this->renderBlade('<x-alert />')
        );
    }

    public function testPropsPreventOutputInAttributes(): void
    {
        $this->createComponent(
            'alert',
            '@props(["name"])<div{{ $attributes }}>Hello, World! {{ $name }}</div>'
        );

        $this->assertSame(
            "<div>Hello, World! Taylor</div>",
            $this->renderBlade('<x-alert name="Taylor" />')
        );
    }

    public function testClosingComponents(): void
    {
        $this->createComponent(
            'alert',
            '<div></div>'
        );

        $this->assertSame(
            "<div></div>",
            $this->renderBlade('<x-alert></x-alert>')
        );
    }

    public function testClosingComponentsWithHyphen(): void
    {
        $this->createComponent(
            'alert-info',
            '<div></div>'
        );

        $this->assertSame(
            "<div></div>",
            $this->renderBlade('<x-alert-info></x-alert-info>')
        );
    }

    public function testClosingComponentsWithDirectoryDot(): void
    {
        $this->createComponent(
            'alerts.info',
            '<div></div>'
        );

        $this->assertSame(
            "<div></div>",
            $this->renderBlade('<x-alerts.info></x-alerts.info>')
        );
    }
}
