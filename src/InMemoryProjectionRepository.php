<?php
declare(strict_types=1);

namespace Example\FastRebuilds;

use function array_map;

class InMemoryProjectionRepository implements SomeProjectionRepository
{
    private array $projections = [];
    private int $persistCallACount = 0;

    public function persist(SomeProjection $projection): void
    {
        $this->projections[$projection->id->toString()] = $projection;
    }

    public function persistMany(SomeProjection ...$projections): void
    {
        $this->persistCallACount++;
        array_map($this->persist(...), $projections);
    }


    public function persistCallACount(): int
    {
        return $this->persistCallACount;
    }

    public function retrieveOrNew(SomeIdentifier $identifier): SomeProjection
    {
        return $this->projections[$identifier->toString()] ?? new SomeProjection($identifier);
    }
}