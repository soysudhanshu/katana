<?php

namespace Blade;

class TemplateInheritanceRenderer
{
    protected string $template = '';
    protected array $sections = [];
    protected bool $renderingParentLayout = false;
    protected string $currentSection = '';

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

        $this->currentSection = $section;

        if (isset($this->sections[$section])) {
            return;
        }

        $this->sections[$section] = (object) [
            'content' => '',
            'defaultContent' => '',
        ];
    }

    public function endSection(): void
    {

        $section = $this->sections[$this->currentSection];

        if ($section->content === '') {
            $section->content = ob_get_clean();
        } else {
            $section->defaultContent = ob_get_clean();
        }
    }

    protected function hasSection(string $section): bool
    {
        return isset($this->sections[$section]);
    }

    protected function getSection(string $name): string
    {
        $section = $this->sections[$name];

        return $section->content ? $section->content : $section->defaultContent;
    }

    public function outputSection(string $type = 'default_content'): void
    {
        $this->endSection();

        $section = $this->sections[$this->currentSection];
        $content = $this->getSection($this->currentSection, $type);

        if ($this->hasParentContentPlaceholder($content)) {
            $content = $this->replaceParentContentPlaceholder($content, $section);
        }

        echo $content;
    }

    protected function hasParentContentPlaceholder(string $content): bool
    {
        return strpos($content, '### DEFAULT SECTION CONTENT ###') !== false;
    }

    protected function replaceParentContentPlaceholder(string $content, object $section): string
    {
        return str_replace(
            '### DEFAULT SECTION CONTENT ###',
            $section->defaultContent,
            $content
        );
    }

    public function output(): void
    {
        $output = ob_get_clean();
        if (trim($output) !== '') {
            echo $output;
        }

        $this->renderingParentLayout = true;

        echo $this->blade->render($this->template);
    }
}
