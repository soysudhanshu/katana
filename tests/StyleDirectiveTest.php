<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class StyleDirectiveTest extends TestCase
{
    use VerifiesOutputTrait;

    public function testSimpleClassDirective()
    {
        $this->assertSame(
            'style="display:none; color:red;"',
            $this->renderBlade('@style(["display:none", "color:red;",])')
        );
    }

    public function testConditionalStyles()
    {
        $this->assertSame(
            'style="display:none;"',
            $this->renderBlade(
                '@style(["display:none" => $isMobile, "color: red" => !$isMobile])',
                ['isMobile' => true]
            )
        );

        $this->assertSame(
            'style="color: red;"',
            $this->renderBlade(
                '@style(["display:none" => $isMobile, "color: red" => !$isMobile])',
                ['isMobile' => false]
            )
        );
    }
}
