<?php
declare(strict_types=1);

namespace Example\FastRebuilds;

class SomeProjection
{
    private int $counter = 0;

    public function __construct(public readonly SomeIdentifier $id)
    {
    }

    public function counter(): int
    {
        return $this->counter;
    }

    public function increase(int $amount = 1): void
    {
        $this->counter += $amount;
    }
}