<?php


namespace Blade\Environments;

trait FragmentEnvironment
{
    public array $fragments = [];

    public function startFragment(string $name): void
    {
        ob_start();

        $this->fragments[$name] = (object)[
            'name' => $name,
            'content' => '',
        ];
    }

    public function endFragment(): void
    {
        echo end($this->fragments)->content = ob_get_clean();
    }
}
