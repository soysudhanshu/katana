<?php

namespace Blade;

use Iterator;
use IteratorAggregate;

class Loop implements Iterator
{
    public array|Iterator $data;

    public bool $first = true;
    public bool $last = false;

    public int $index = 0;
    public int $iteration = 1;

    public int $count = 0;
    public int $remaining = 0;

    public bool $even = false;
    public bool $odd = false;

    public ?self $parent = null;
    public int $depth = 0;

    public function setData($data, ?self $parent = null): self
    {
        if ($data instanceof IteratorAggregate) {
            $data = $data->getIterator();
        }

        $this->data = $data;
        $this->parent = $parent;

        return $this;
    }

    public function next(): void
    {
        $this->first = false;

        $this->index++;
        $this->iteration++;

        $this->even = $this->iteration % 2 === 0;
        $this->odd = !$this->even;

        if ($this->count) {
            $this->remaining = $this->count - $this->iteration;
        }

        $this->last = $this->iteration === $this->count;

        if ($this->data instanceof Iterator) {
            $this->data->next();
            return;
        }

        next($this->data);
    }

    public function current()
    {
        if ($this->data instanceof Iterator) {
            return $this->data->current();
        }

        return current($this->data);
    }

    public function key()
    {
        if ($this->data instanceof Iterator) {
            return $this->data->key();
        }

        return key($this->data);
    }

    public function valid(): bool
    {
        return $this->key() !== null;
    }

    public function rewind(): void
    {
        $this->index = 0;
        $this->iteration = 1;
        $this->remaining = 0;

        $this->first = true;
        $this->last = false;

        $this->even = $this->iteration % 2 === 0;
        $this->odd = !$this->even;

        if ($this->parent) {
            $this->depth = $this->parent->depth + 1;
        }

        if (is_countable($this->data)) {
            $this->count = count($this->data);
            $this->remaining = $this->count - $this->iteration;

            $this->last = $this->count === 1;
        }

        if ($this->data instanceof Iterator) {
            $this->data->rewind();
            return;
        }

        reset($this->data);
    }
}
