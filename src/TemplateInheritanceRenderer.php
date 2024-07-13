<?php

namespace Blade;

class TemplateInheritanceRenderer
{
    protected string $template = '';
    protected array $sections = [];

    public function __construct(protected Blade $blade)
    {
    }

    public function extends(string $template): void
    {
        $this->template = $template;
        ob_start();
    }

    public function yield(string $section, string $fallbackContent = ''): string
    {
        if ($this->hasSection($section)) {
            return $this->getSection($section);
        }

        return  $fallbackContent;
    }

    public function startSection(string $section): void
    {
        ob_start();

        $this->sections[$section] = null;
    }

    public function endSection(): void
    {
        $this->sections[array_key_last($this->sections)] = ob_get_clean();
    }

    protected function hasSection(string $section): bool
    {
        return isset($this->sections[$section]);
    }

    protected function getSection(string $section): string
    {
        return $this->sections[$section];
    }

    public function output(): void
    {
        $output = ob_get_clean();
        if (trim($output) !== '') {
            echo $output;
        }

        echo $this->blade->render($this->template);
    }
}
