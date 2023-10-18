<?php
declare(strict_types=1);

namespace Example\FastRebuilds;

use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageConsumer;

class SomeProjector implements MessageConsumer
{
    public function __construct(
        private readonly SomeProjectionRepository $repository,
    ) {
    }

    public function handle(Message $message): void
    {
        $event = $message->event();
        $id = $message->aggregateRootId();
        assert($id instanceof SomeIdentifier);

        if ($event instanceof SomethingWasIncreased) {
            $projection = $this->repository->retrieveOrNew($id);
            $projection->increase($event->amount);
            $this->repository->persist($projection);
        }
    }
}