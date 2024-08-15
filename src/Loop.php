<?php

namespace Blade;

use Iterator;

class Loop
{
    public bool $first = true;
    public int $index = 0;
    public int $iteration = 1;
    public int $count = 0;

    public function setData($data)
    {
        if (is_countable($data)) {
            $this->count = count($data);
        }

        return $data;
    }

    public function increment()
    {
        $this->index++;
        $this->iteration++;

        $this->first = $this->index === 0;
    }
}
