<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class FormDirectiveTest extends TestCase
{
    use VerifiesOutputTrait;

    public function testRequiredDirective(): void
    {
        $this->assertSame(
            '<input type="text" name="name" required>',
            $this->renderBlade('<input type="text" name="name" @required>')
        );
    }

    public function testRequiredWithConditionals(): void
    {
        $cases = [
            [
                'condition' => 'true',
                'expected' => '<input type="text" name="name" required>',
                'message' => 'The input should be required when the condition is true',
            ],
            [
                'condition' => 'false',
                'expected' => '<input type="text" name="name" >',
                'message' => 'The input should not be required when the condition is false',
            ],
            [
                'condition' => 'null',
                'expected' => '<input type="text" name="name" >',
                'message' => 'The input should not be required when the condition is null',
            ],
            [
                'condition' => 'fn() => true',
                'expected' => '<input type="text" name="name" required>',
                'message' => 'The input should be required when the condition is a string that evaluates to true',
            ],
            [
                'condition' => '1 == "1"',
                'expected' => '<input type="text" name="name" required>',
                'message' => 'The input should be required when the condition is a string that evaluates to true',
            ]
        ];

        foreach ($cases as $case) {
            $this->assertSame(
                $case['expected'],
                $this->renderBlade(
                    '<input type="text" name="name" @required(' . $case['condition'] . ')>',
                ),
                $case['message']
            );
        }
    }

    public function testDisabledDirective(): void
    {
        $this->assertSame(
            '<input type="text" name="name" disabled>',
            $this->renderBlade('<input type="text" name="name" @disabled>')
        );
    }

    public function testDisabledDirectiveWithConditionals(): void
    {
        $cases = [
            [
                'condition' => 'true',
                'expected' => '<input type="text" name="name" disabled>',
                'message' => 'The input should be disabled when the condition is true',
            ],
            [
                'condition' => 'false',
                'expected' => '<input type="text" name="name" >',
                'message' => 'The input should not be disabled when the condition is false',
            ],
            [
                'condition' => 'null',
                'expected' => '<input type="text" name="name" >',
                'message' => 'The input should not be disabled when the condition is null',
            ],
            [
                'condition' => 'fn() => true',
                'expected' => '<input type="text" name="name" disabled>',
                'message' => 'The input should be disabled when the condition is a string that evaluates to true',
            ],
            [
                'condition' => '1 == "1"',
                'expected' => '<input type="text" name="name" disabled>',
                'message' => 'The input should be disabled when the condition is a string that evaluates to true',
            ]
        ];

        foreach ($cases as $case) {
            $this->assertSame(
                $case['expected'],
                $this->renderBlade(
                    '<input type="text" name="name" @disabled(' . $case['condition'] . ')>',
                ),
                $case['message']
            );
        }
    }
}
