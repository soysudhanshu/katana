<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class ComponentDirectiveTest extends TestCase
{
    use VerifiesOutputTrait;

    public function testRendersComponent(): void
    {
        $this->createTemporaryBladeFile(
            "Component Content",
            'component'
        );

        $this->assertSame(
            "Component Content",
            $this->renderBlade("@component('component') @endcomponent")
        );
    }

    public function testPassesData(): void
    {
        $this->createTemporaryBladeFile(
            "Hello, {{ \$name }}",
            'component'
        );

        $this->assertSame(
            "Hello, John",
            $this->renderBlade("@component('component', ['name' => 'John']) @endcomponent")
        );
    }

    public function testAttributes(): void
    {
        $this->markTestSkipped('Requires implementation');

        $this->createTemporaryBladeFile(
            "Hello, {{ \$attributes }}",
            'component'
        );

        $this->assertSame(
            "Hello, John",
            $this->renderBlade("@component('component', ['name' => 'John']) @endcomponent")
        );
    }
}
