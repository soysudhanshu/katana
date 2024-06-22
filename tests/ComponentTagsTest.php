<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class ComponentTagsTest extends TestCase
{
    use VerifiesOutputTrait;

    public function testCompilesTagIntoDirective()
    {
        $this->createComponent('alert', '<div>Hello, World!</div>');

        $this->assertSame(
            "<div>Hello, World!</div>",
            $this->renderBlade("<x-alert />")
        );
    }

    public function testCompilesTagWithAttributes()
    {
        $this->createComponent('alert', '<div>Hello, World!</div>');

        $this->assertSame(
            "<div>Hello, World!</div>",
            $this->renderBlade("<x-alert/>")
        );
    }

}
