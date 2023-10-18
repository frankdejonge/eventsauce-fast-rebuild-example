<?php

include __DIR__ . '/vendor/autoload.php';

use EventSauce\EventSourcing\InMemoryMessageRepository;
use EventSauce\EventSourcing\ReplayingMessages\ReplayMessages;
use EventSauce\EventSourcing\OffsetCursor;
use Example\FastRebuilds\BufferedProjectionRepository;
use Example\FastRebuilds\InMemoryProjectionRepository;
use Example\FastRebuilds\ProduceManyEvents;
use Example\FastRebuilds\RandomIdentifier;
use Example\FastRebuilds\SomeProjector;

// Setup
$actualRepository = new InMemoryProjectionRepository();
$bufferedRepository = new BufferedProjectionRepository(
    actualRepository: $actualRepository,
    maxBufferSize: 75,
);
$messages = new InMemoryMessageRepository();
$messageProducer = new ProduceManyEvents();

// Produce random messages
$messageProducer->produceRandomEvents(
    new RandomIdentifier(100),
    $messages,
    amount: 15000,
);

$replayMessages = new ReplayMessages(
    $messages,
    new SomeProjector(
        $bufferedRepository,
    ),
);

$cursor = OffsetCursor::fromStart(limit: 1000);

process_batch:
$result = $replayMessages->replayBatch($cursor);
$cursor = $result->cursor();

if (($handled = $result->messagesHandled()) > 0) {
    goto process_batch;
}

$bufferedRepository->flush();

fwrite(STDOUT, 'cheap count: ' . $bufferedRepository->persistCallAmount() . PHP_EOL);
fwrite(STDOUT, 'expensive count: ' . $actualRepository->persistCallACount() . PHP_EOL);