<?php
declare(strict_types=1);

namespace Example\FastRebuilds;

use EventSauce\EventSourcing\Header;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageRepository;
use function count;
use function random_int;

class ProduceManyEvents
{
    public function produceRandomEvents(
        RandomIdentifier $identifiers,
        MessageRepository $repository,
        int $amount,
    ) {
        $batch = [];
        $batchSize = 100;

        for ($i = 0; $i < $amount; $i++) {
            $batch[] = (new Message(new SomethingWasIncreased(random_int(0, 100))))->withHeader(
                Header::AGGREGATE_ROOT_ID,
                $identifiers->identifier(),
            );

            if (count($batch) === $batchSize) {
                $repository->persist(...$batch);
                $batch = [];
            }
        }

        if (count($batch) > 0) {
            $repository->persist(...$batch);
        }
    }
}