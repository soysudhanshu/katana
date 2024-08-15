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
        $this->remaining = $this->count;
        
        reset($this->data);
    }

    public function increment() {}
}
