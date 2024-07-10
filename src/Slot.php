<?php

namespace Blade;

use Attribute;
use Blade\Interfaces\HtmlableInterface;


/**
 * Represents a slot inside a component.
 */
class Slot implements HtmlableInterface
{
    public function __construct(
        private string $slot,
        public readonly Attributes $attributes
    ) {
    }

    public function toHtml(): string
    {
        return $this->slot;
    }

    public function __toString(): string
    {
        return $this->toHtml();
    }
}
