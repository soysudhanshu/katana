<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class ComponentSlotsTest extends TestCase
{
    use VerifiesOutputTrait;

    public function testDefaultSlot()
    {
        $this->createComponent('card', "<div class='card'>{{ \$slot }}</div>");

        $this->assertEquals(
            '<div class=\'card\'>Hello, World!</div>',
            $this->renderBlade('<x-card>Hello, World!</x-card>')
        );
    }

    public function testNamedSlot()
    {
        $this->createComponent(
            'card',
            "<div class='card'> {{ \$header }} {{ \$slot }} </div>"
        );


        $blade = <<<'BLADE'
        <x-card>
            <x-slot name="header">
                Maria's Guide to the Galaxy
            </x-slot>
            <p>Don't Panic</p>
        </x-card>
        BLADE;


        $this->assertEquals(
            "<div class='card'> Maria's Guide to the Galaxy <p>Don't Panic</p> </div>",
            $this->removeIndentation($this->renderBlade($blade))
        );
    }

    public function testMultipleNamedSlot(): void
    {
        $this->createComponent(
            'card',
            "<div class='card'> {{ \$header }} {{ \$slot }} {{ \$footer }} </div>"
        );

        $blade = <<<'BLADE'
        <x-card>
            <x-slot name="header">
                Maria's Guide to the Galaxy
            </x-slot>

            <x-slot name="footer">
                <footer>
                    That's all, folks!
                </footer>
            </x-slot>

            <article>
                <p>Don't Panic</p>
            </article>
        </x-card>
        BLADE;

        $this->assertEquals(
            "<div class='card'> Maria's Guide to the Galaxy " .
                "<article> <p>Don't Panic</p> </article> " .
                "<footer> That's all, folks! </footer> " .
            "</div>",
            $this->removeIndentation($this->renderBlade($blade))
        );
    }
}
