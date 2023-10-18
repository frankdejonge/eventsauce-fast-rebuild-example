<?php
declare(strict_types=1);

namespace Example\FastRebuilds;

use function array_map;
use function count;

class BufferedProjectionRepository implements SomeProjectionRepository
{
    private array $buffer = [];
    private int $persistCallAmount = 0;

    public function __construct(
        private readonly SomeProjectionRepository $actualRepository,
        private readonly int $maxBufferSize = 100,
    )
    {
    }

    public function persist(SomeProjection $projection): void
    {
        $this->persistCallAmount++;
        $this->buffer[$projection->id->toString()] = $projection;

        if (count($this->buffer) > $this->maxBufferSize) {
            $this->flush();
        }
    }

    public function persistMany(SomeProjection ...$projections): void
    {
        array_map($this->persist(...), $projections);
    }

    public function persistCallAmount(): int
    {
        return $this->persistCallAmount;
    }

    public function retrieveOrNew(SomeIdentifier $identifier): SomeProjection
    {
        return $this->buffer[$identifier->toString()] ?? $this->actualRepository->retrieveOrNew($identifier);
    }

    public function flush(): void
    {
        $this->actualRepository->persistMany(...$this->buffer);
        $this->buffer = [];
    }
}