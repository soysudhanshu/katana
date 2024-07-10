<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class PhpBlockTest extends TestCase
{
    use VerifiesOutputTrait;

    public function testBlock(): void
    {
        $blade = '@php $name = "John Doe"; echo "Hello $name"; @endphp';
        $this->assertEquals(
            'Hello John Doe',
            $this->renderBlade($blade)
        );
    }

    public function testDoesNotRenderDirectives(): void
    {
        $conditions = [
            [
                'blade' => '@php echo "@if(true) @endif"; @endphp',
                'output' => '@if(true) @endif',
            ],
            [
                'blade' => "@php echo '{!! date(\'Y\') !!}' @endphp",
                'output' => "{!! date('Y') !!}",
            ],
            [
                'blade' => '@php echo "{{ date(\'Y\') }}"; @endphp',
                'output' => "{{ date('Y') }}",
            ],
        ];

        foreach ($conditions as $condition) {
            $this->assertEquals(
                $condition['output'],
                $this->renderBlade($condition['blade'])
            );
        }
    }
}
