<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class ClassDirectiveTest extends TestCase
{
    use VerifiesOutputTrait;

    public function testSimpleClassDirective()
    {
        $this->assertSame(
            'class="bg-reg border shadow"',
            $this->renderBlade('@class(["bg-reg", "border", "shadow"])')
        );
    }


    public function testConditionalClasses(){
        $this->assertSame(
            'class="bg-red"',
            $this->renderBlade('@class(["bg-red", "text-red" => false])')
        );

        $this->assertSame(
            'class="bg-white text-red"',
            $this->renderBlade('@class(["bg-white", "text-red" => true])')
        );
    }
}
