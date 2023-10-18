<?php
declare(strict_types=1);

namespace Example\FastRebuilds;

class SomethingWasIncreased
{
    public function __construct(public readonly int $amount)
    {
    }
}