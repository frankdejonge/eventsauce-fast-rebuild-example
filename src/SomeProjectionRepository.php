<?php
declare(strict_types=1);

namespace Example\FastRebuilds;

interface SomeProjectionRepository
{
    public function persist(SomeProjection $projection): void;
    public function persistMany(SomeProjection ...$projections): void;
    public function retrieveOrNew(SomeIdentifier $identifier): SomeProjection;
}