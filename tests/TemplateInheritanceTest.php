<?php

namespace  Tests;

use PHPUnit\Framework\TestCase;

class TemplateInheritanceTest extends TestCase
{
    use VerifiesOutputTrait;

    public function testOnlyExtends()
    {
        $layout = <<<LAYOUT
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Layout</title>
        </head>
        <body>
        </body>
        </html>
        LAYOUT;

        $this->createTemporaryBladeFile($layout, 'layout');

        $this->assertSame(
            $layout,
            $this->renderBlade('@extends("layout")')
        );
    }


    public function testYieldWithDefaultContent(): void
    {

        $layout = <<<LAYOUT
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Layout</title>
        </head>
        <body>%s</body>
        </html>
        LAYOUT;

        $this->createTemporaryBladeFile(
            sprintf($layout, "@yield('content', 'Default Content')"),
            'layout'
        );

        $this->assertSame(
            sprintf($layout, "Default Content"),
            $this->renderBlade('@extends("layout")')
        );
    }

    public function testYieldWithSection(): void
    {
        $layout = <<<LAYOUT
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Layout</title>
        </head>
        <body>%s</body>
        </html>
        LAYOUT;

        $this->createTemporaryBladeFile(
            sprintf($layout, "@yield('content')"),
            'layout'
        );

        $this->assertSame(
            sprintf($layout, " Section Content "),
            $this->renderBlade('@extends("layout") @section("content") Section Content @endsection')
        );
    }
}
