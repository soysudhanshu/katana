<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class ForeachLoopTest extends TestCase
{
    use VerifiesOutputTrait;

    public function test_basic_foreach()
    {
        $this->assertEquals(
            $this->renderBlade('@foreach([1, 2, 3] as $item){{ "Hello" }}@endforeach'),
            "HelloHelloHello"
        );
    }

    public function test_foreach_with_key()
    {
        $numbers = [1, 2, 3];

        $output = $this->renderBlade(
            '@foreach($numbers as $key => $value) {{ $key }} => {{ $value }} @endforeach',
            [
                'numbers' => $numbers,
            ]
        );

        foreach ($numbers as $key => $value) {
            $this->assertStringContainsString("$key => $value", $output);
        }
    }
}