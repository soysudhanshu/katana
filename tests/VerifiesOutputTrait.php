<?php

namespace Tests;

use Blade\Blade;

trait VerifiesOutputTrait
{
    private array $createdFiles = [];


    public function tearDown(): void
    {

        foreach ($this->createdFiles as $file) {
            unlink($file);
        }

        parent::tearDown();
    }

    private function getTempDirectory(): string
    {
        static $directory;

        if (is_null($directory)) {
            $directory = __DIR__ . '/tmp';
            sys_get_temp_dir();
        }

        return $directory;
    }

    /**
     * Undocumented function
     *
     * @param string $template
     * @return string
     */
    private function createTemporaryBladeFile(string $template, string $name = '', bool $isComponent = false): string
    {
        if (empty($name)) {
            $name = hash('sha256', $template);
        }

        $file = $this->getTempDirectory() . "/{$name}.blade.php";

        if ($isComponent) {
            $this->maybeCreateComponentDirectory();
            $file = $this->getTempDirectory() . "/components/{$name}.blade.php";
        }


        if (file_put_contents($file, $template) === false) {
            throw new \Exception('Could not create temporary file');
        }

        $this->createdFiles[] = $file;

        return $name;
    }

    private function maybeCreateComponentDirectory(): void
    {
        $directory = $this->getTempDirectory() . '/components';

        if (!is_dir($directory)) {
            mkdir($directory);
        }
    }


    public function renderBlade($template, $data = [])
    {
        $name = $this->createTemporaryBladeFile(template: $template);

        $blade = new Blade(
            $this->getTempDirectory(),
            $this->getTempDirectory()
        );

        ob_start();
        $blade->render($name, $data);
        return ob_get_clean();
    }

    public function createComponent(string $name, string $template, $data = [])
    {
        $name = $this->createTemporaryBladeFile(
            $template,
            $name,
            true
        );
        // dd($name);
    }
}
