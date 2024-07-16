<?php

namespace  Tests;

use PHPUnit\Framework\TestCase;

class TemplateInheritanceTest extends TestCase
{
    use VerifiesOutputTrait;

    public const LAYOUT = <<<LAYOUT
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Layout</title>
        </head>
        <body>%s</body>
        </html>
        LAYOUT;

    public function testOnlyExtends()
    {
        $this->createTemporaryBladeFile(self::LAYOUT, 'layout');

        $this->assertSame(
            self::LAYOUT,
            $this->renderBlade('@extends("layout")')
        );
    }


    public function testYieldWithDefaultContent(): void
    {
        $this->createTemporaryBladeFile(
            sprintf(self::LAYOUT, "@yield('content', 'Default Content')"),
            'layout'
        );

        $this->assertSame(
            sprintf(self::LAYOUT, "Default Content"),
            $this->renderBlade('@extends("layout")')
        );
    }

    public function testYieldWithSection(): void
    {
        $this->createTemporaryBladeFile(
            sprintf(self::LAYOUT, "@yield('content')"),
            'layout'
        );

        $this->assertSame(
            sprintf(self::LAYOUT, " Section Content "),
            $this->renderBlade('@extends("layout") @section("content") Section Content @endsection')
        );
    }

    public function testSectionDoesntOutputByDefault(): void
    {
        $this->createTemporaryBladeFile(
            sprintf(self::LAYOUT, "@section('content') It should show @endsection"),
            'layout'
        );

        $this->assertSame(
            sprintf(self::LAYOUT, ""),
            $this->renderBlade('@extends("layout")')
        );
    }

    public function testShowDirectiveSectionOutput(): void
    {
        $this->createTemporaryBladeFile(
            sprintf(self::LAYOUT, "@section('content') It should show @show"),
            'layout'
        );

        $this->assertSame(
            sprintf(self::LAYOUT, " It should show "),
            $this->renderBlade('@extends("layout")')
        );
    }

    public function testDefaultSectionOverride(): void
    {
        $this->createTemporaryBladeFile(
            sprintf(self::LAYOUT, "@section('content') Default Content @show"),
            'layout'
        );

        $this->assertSame(
            sprintf(self::LAYOUT, " Section Content "),
            $this->renderBlade('@extends("layout") @section("content") Section Content @endsection')
        );
    }

    public function testParentRule(): void
    {
        $this->createTemporaryBladeFile(
            sprintf(self::LAYOUT, "@section('content') Default Content @show"),
            'layout'
        );

        $this->assertSame(
            $this->removeIndentation(
                sprintf(self::LAYOUT, " Default Content Section Content ")
            ),
            $this->removeIndentation(
                $this->renderBlade(
                    "@extends('layout') @section('content') @parent Section Content @endsection"
                )
            )
        );
    }

    public function testMultipleSections(): void
    {
        $this->createTemporaryBladeFile(
            sprintf(
                self::LAYOUT,
                "@section('content') Default Content @show @section('footer') Footer @show"
            ),
            'layout'
        );


        $blade = "@extends('layout') " .
            "@section('content') Section Content @endsection " .
            "@section('footer') Footer Override @endsection";

        $this->assertSame(
            sprintf(self::LAYOUT, " Section Content  Footer Override "),
            $this->renderBlade($blade)
        );
    }

    public function testMultipleSectionsWithDefaultContent(): void
    {
        $this->createTemporaryBladeFile(
            sprintf(
                self::LAYOUT,
                "@section('content') Default Content @show @section('footer') Default Footer @show"
            ),
            'layout'
        );

        $this->assertSame(
            sprintf(self::LAYOUT, " Section Content  Default Footer "),
            $this->renderBlade(
                "@extends('layout') @section('content') Section Content @endsection"
            )
        );
    }

    public function testHasSection(): void
    {
        $this->createTemporaryBladeFile(
            sprintf(
                self::LAYOUT,
                "@hasSection('content') Default Content @yield('content') @endif"
            ),
            'layout'
        );

        $this->assertSame(
            sprintf(self::LAYOUT, " Default Content  Section Content  "),
            $this->renderBlade(
                "@extends('layout') @section('content') Section Content @endsection"
            )
        );
    }

    public function testSectionMissing(): void
    {
        $this->createTemporaryBladeFile(
            sprintf(
                self::LAYOUT,
                "Content Before @sectionMissing('content') Default Content @endif After Content"
            ),
            'layout'
        );

        $this->assertSame(
            sprintf(self::LAYOUT, "Content Before  Default Content After Content"),
            $this->renderBlade(
                "@extends('layout')"
            )
        );
    }
}
