<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class IncludeDirectiveTest extends TestCase
{
    use VerifiesOutputTrait;

    public function testIncludeDirective()
    {
        $this->createTemporaryBladeFile('This is sub view content', 'subview');

        $this->assertSame(
            'This is the main view. This is sub view content',
            $this->renderBlade('This is the main view. @include("subview")')
        );
    }

    public function testIncludeDirectiveWithVariables()
    {
        $this->createTemporaryBladeFile('Hello, {{ $name }}', 'subview');

        $this->assertSame(
            'This is the main view. Hello, John',
            $this->renderBlade('This is the main view. @include("subview", ["name" => "John"])')
        );
    }


    public function testIncludeDirectiveInheritsVariables()
    {
        $this->createTemporaryBladeFile('Hello, {{ $name }}', 'subview');

        $this->assertSame(
            'This is the main view. Hello, John',
            $this->renderBlade(
                'This is the main view. @include("subview")',
                ['name' => 'John']
            )
        );
    }

    public function testIncludeIf(): void
    {
        $this->createTemporaryBladeFile('This is sub view content', 'subview');

        $this->assertSame(
            'This is the main view. This is sub view content',
            $this->renderBlade('This is the main view. @includeIf("subview")')
        );
    }

    public function testIncludeIfWithVariables(): void
    {
        $this->createTemporaryBladeFile('Hello, {{ $name }}', 'subview');

        $this->assertSame(
            'This is the main view. Hello, John',
            $this->renderBlade('This is the main view. @includeIf("subview", ["name" => "John"])')
        );
    }

    public function testIncludeIfInheritsVariables(): void
    {
        $this->createTemporaryBladeFile('Hello, {{ $name }}', 'subview');

        $this->assertSame(
            'This is the main view. Hello, John',
            $this->renderBlade(
                'This is the main view. @includeIf("subview")',
                ['name' => 'John']
            )
        );
    }

    public function testIncludeIfDoesntThrowErrorWhenViewNotFound(): void
    {
        $this->assertSame(
            'This is the main view. ',
            $this->renderBlade('This is the main view. @includeIf("non-existing-subview")')
        );
    }
}
