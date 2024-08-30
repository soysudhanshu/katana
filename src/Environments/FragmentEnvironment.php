<?php


namespace Blade\Environments;

use Exception;

trait FragmentEnvironment
{
    public array $fragments = [];
    public array $fragmentStack = [];

    public function startFragment(string $name): void
    {
        ob_start();

        $this->fragmentStack[] = $name;
    }

    public function endFragment(): void
    {
        if (empty($this->fragmentStack)) {
            throw new Exception('Cannot end a fragment without starting one.');
        }

        $fragment = array_pop($this->fragmentStack);

        echo $this->fragments[$fragment] = ob_get_clean();
    }

    public function getFragment(string $name): string
    {
        return $this->fragments[$name] ?? '';
    }
}
