<?php

namespace Blade;

use Iterator;
use IteratorAggregate;

class Loop implements Iterator
{
    public array $data;
    public bool $first = true;
    public int $index = 0;
    public int $iteration = 1;
    public int $count = 0;
    public int $remaining = 0;
    public bool $even = false;
    public bool $odd = false;
    public bool $last = false;

    public function setData($data)
    {
        $this->data = $data;

        if (is_countable($data)) {
            $this->count = count($data);
            $this->remaining = $this->count - $this->iteration;
        }

        return $this;
    }

    public function next(): void
    {
        $this->index++;
        $this->iteration++;

        $this->first = false;

        if ($this->count) {
            $this->remaining = $this->count - $this->iteration;
        }

        if ($this->iteration === $this->count) {
            $this->last = true;
        }

        $this->even = $this->iteration % 2 === 0;
        $this->odd = !$this->even;

        next($this->data);
    }

    public function current()
    {
        return current($this->data);
    }

    public function key()
    {
        return key($this->data);
    }

    public function valid(): bool
    {
        return $this->key() !== null;
    }

    public function rewind(): void
    {
        $this->first = true;
        $this->index = 0;
        $this->iteration = 1;
        $this->remaining = $this->count - $this->iteration;

        if ($this->count && $this->iteration === $this->count) {
            $this->last = true;
        } else {
            $this->last = false;
        }

        $this->even = $this->iteration % 2 === 0;
        $this->odd = !$this->even;

        reset($this->data);
    }

    public function increment() {}
}
