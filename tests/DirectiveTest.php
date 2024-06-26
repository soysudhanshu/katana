<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class DirectiveTest extends TestCase
{
    use VerifiesOutputTrait;

    public function testMultipleLineDirective()
    {
        $this->assertStringContainsString(
            "Voted by Mail",
            $this->renderBlade(
                "@if(\$hasVoted && \n\$hasMailedVote ) Voted by Mail @endif",
                [
                    "hasVoted" => true,
                    "hasMailedVote" => true,
                ]
            )
        );
    }

    public function testMultiLineProps(): void
    {
        $this->createComponent(
            'alert',
            '@props([
                "type" => "info",
                "message" => "Everything is going well",
                "time" => time(),
            ])
            {{ $message }} at {{ $time }}',
        );


        $this->assertStringContainsString(
            'Everything is going well at ' . time(),
            $this->renderBlade('<x-alert/>')
        );
    }
}
