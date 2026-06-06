<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class AnonymousIndexComponentTest extends TestCase
{
    use VerifiesOutputTrait;

    public function testIndexComponent(): void
    {
        $this->createComponent('slider.index', "Slider");

        $this->assertSame(
            "Slider",
            $this->renderBlade("<x-slider />")
        );
    }

    public function testComponentFileTakesPriorityOverIndexComponent(): void
    {
        $this->createComponent('slider.index', "Index slider");
        $this->createComponent('slider', "Base slider");


        $this->assertSame(
            "Base slider",
            $this->renderBlade("<x-slider />")
        );
    }
}
