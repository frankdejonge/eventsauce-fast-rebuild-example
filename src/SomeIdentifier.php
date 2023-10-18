<?php
declare(strict_types=1);

namespace Example\FastRebuilds;

use EventSauce\EventSourcing\AggregateRootId;
use Ramsey\Uuid\Uuid;

readonly class SomeIdentifier implements AggregateRootId
{
    private function __construct(private string $uuid)
    {
    }

    public static function generate(): static
    {
        return new SomeIdentifier(Uuid::uuid4()->toString());
    }


    public function toString(): string
    {
        return $this->uuid;
    }

    public static function fromString(string $aggregateRootId): static
    {
        return new SomeIdentifier($aggregateRootId);
    }
}