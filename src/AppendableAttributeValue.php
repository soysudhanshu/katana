<?php

namespace Blade;

use Stringable;

/**
 * Represents an attribute value that should be appended
 * to the existing attribute value.
 */
final class AppendableAttributeValue implements Stringable
{
    public function __construct(private string $value)
    {
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
