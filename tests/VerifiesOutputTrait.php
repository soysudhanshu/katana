<?php

namespace Tests;

use Blade\Blade;

trait VerifiesOutputTrait
{
    private array $createdFiles = [];

    protected Blade $blade;

    protected function setup(): void
    {
        parent::setUp();

        if (!is_dir($this->getTempDirectory())) {
            mkdir($this->getTempDirectory());
        }

        $this->blade = new Blade(
            $this->getTempDirectory(),
            $this->getTempDirectory()
        );
    }


    public function tearDown(): void
    {

        foreach ($this->createdFiles as $file) {
            if(!file_exists($file)){
                continue;
            }
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

        if($isComponent){
            $name = 'components.' . $name;
        }

        if (str_contains($name, '.')) {
            $name = str_replace('.', '/', $name);
        }

        $file = $this->getTempDirectory() . "/{$name}.blade.php";


        if ($isComponent) {
            $directory = pathinfo($file, PATHINFO_DIRNAME);
            $this->maybeCreateComponentDirectory($directory);
        }


        if (file_put_contents($file, $template) === false) {
            throw new \Exception('Could not create temporary file');
        }

        $this->createdFiles[] = $file;

        return $name;
    }

    private function maybeCreateComponentDirectory(string $directory): void
    {
        if (!is_dir($directory)) {
            mkdir(
                directory: $directory,
                recursive: true
            );
        }
    }


    public function renderBlade($template, $data = [])
    {
        $name = $this->createTemporaryBladeFile(template: $template);

        ob_start();
        $this->blade->render($name, $data);
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
