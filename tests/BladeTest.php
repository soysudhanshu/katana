<?php

namespace Tests;

use Exception;
use PHPUnit\Framework\TestCase;

class BladeTest extends TestCase
{
    use VerifiesOutputTrait;


    public function testLoadsComponentFromAdditionalPath(): void
    {
        $additionalDir = $this->blade->viewPath . '/additional-components';

        mkdir($additionalDir, recursive: true);

        file_put_contents(
            sprintf('%s/%s', $additionalDir, 'external-view.blade.php'),
            'external view'
        );

        $this->blade->addAnonymousComponentPath($additionalDir);

        $this->assertSame(
            'external view',
            $this->renderBlade('<x-external-view />')
        );
    }
}
