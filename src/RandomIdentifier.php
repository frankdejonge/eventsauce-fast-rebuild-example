<?php
declare(strict_types=1);

namespace Example\FastRebuilds;

use function random_int;

class RandomIdentifier
{
    private array $identifiers = [];

    public function __construct(private readonly int $amount)
    {
        for ($i = 0; $i < $this->amount; $i++) {
            $this->identifiers[] = SomeIdentifier::generate();
        }
    }

    public function identifier(): SomeIdentifier
    {
        return $this->identifiers[random_int(0, $this->amount - 1)];
    }
}