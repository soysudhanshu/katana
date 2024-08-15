<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class LoopVariableTest extends TestCase
{
    use VerifiesOutputTrait;

    public function testForeachExposesLoopIndex()
    {
        $output = $this->renderBlade(
            '@foreach([1, 2, 3] as $item){{ $loop->index }}@endforeach'
        );

        $this->assertEquals($output, "012");
    }

    public function testForeachExposesLoopIteration()
    {
        $output = $this->renderBlade(
            '@foreach([1, 2, 3] as $item){{ $loop->iteration }}@endforeach'
        );

        $this->assertEquals($output, "123");
    }

    public function testForeachExposesLoopFirst()
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

    public function testNestedLoopVariable(): void
    {
        $blade = <<<'BLADE'
            @foreach([1, 2, 3] as $item)
                {{ $loop->iteration }}
                @foreach([4] as $nestedItem)
                    {{ $loop->iteration }}
                @endforeach
            @endforeach
        BLADE;

        $this->assertEquals(
            "1 1 2 1 3 1",
            pregReplace('/\s+/', ' ', trim($this->renderBlade($blade)))
        );
    }
}
