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

    public function test_foreach_with_empty()
    {
        $this->assertEmpty(
            $this->renderBlade('@foreach([] as $item){{ "Hello" }}@endforeach')
        );
    }

    public function test_foreach_with_nested_parenthesis()
    {
        $this->assertEquals(
            $this->renderBlade('@foreach(range(0, 9) as $item){{ $item }}@endforeach'),
            "0123456789"
        );
    }

    public function test_foreach_exposes_loop_index()
    {
        $output = $this->renderBlade(
            '@foreach([1, 2, 3] as $item){{ $loop->index }}@endforeach'
        );

        $this->assertEquals($output, "012");
    }

    public function test_foreach_exposes_loop_iteration()
    {
        $output = $this->renderBlade(
            '@foreach([1, 2, 3] as $item){{ $loop->iteration }}@endforeach'
        );

        $this->assertEquals($output, "123");
    }

    public function test_foreach_exposes_loop_first()
    {
        $output = $this->renderBlade(
            '@foreach([1, 2, 3] as $item)' .
                '@if($loop->first)Only first!@endif' .
            '@endforeach'
        );

        $this->assertEquals(
            $output,
            "Only first!"
        );
    }
}
