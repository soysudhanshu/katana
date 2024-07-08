<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class ComponentAttributeBagTest extends TestCase
{
    use VerifiesOutputTrait;

    public function testClassesWithMerge(): void
    {
        $this->createComponent('alert', '<div {{ $attributes->merge(["class" => "alert"]) }}></div>');

        $this->assertSame(
            '<div class=\'big alert\'></div>',
            $this->renderBlade('<x-alert class="big"/>')
        );
    }

    public function testClassMethodWithArray(): void
    {
        $this->createComponent('alert', '<div {{ $attributes->class(["alert"]) }}></div>');

        $this->assertSame(
            '<div class=\'alert\'></div>',
            $this->renderBlade('<x-alert />')
        );
    }

    public function testClassMethodWithConditionalClasses(): void
    {
        $this->createComponent('alert', '<div {{ $attributes->class(["alert", "hidden" => true]) }}></div>');

        $this->assertSame(
            '<div class=\'alert hidden\'></div>',
            $this->renderBlade('<x-alert />')
        );

        $this->createComponent('alert', '<div {{ $attributes->class(["alert", "hidden" => false]) }}></div>');

        $this->assertSame(
            '<div class=\'alert\'></div>',
            $this->renderBlade('<x-alert />')
        );
    }

    public function testClassMethodWithString(): void
    {
        $this->createComponent('alert', '<div {{ $attributes->class("alert") }}></div>');

        $this->assertSame(
            '<div class=\'alert\'></div>',
            $this->renderBlade('<x-alert />')
        );
    }
}
