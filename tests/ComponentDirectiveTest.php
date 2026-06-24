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
        $this->createTemporaryBladeFile(
            "Hello, {{ \$name }}",
            'component'
        );

        $this->assertSame(
            "Hello, John",
            $this->renderBlade("@component('component', ['name' => 'John']) @endcomponent")
        );
    }

    public function testUnderscoreAttributeName(): void
    {
        $this->createTemporaryBladeFile(
            "Hello, {{ \$first_name }}",
            'component'
        );

        $this->assertSame(
            "Hello, John",
            $this->renderBlade("@component('component', ['first_name' => 'John']) @endcomponent")
        );
    }
}
