<?php

namespace Devian2011\Seeder\Events\Listeners;

use Devian2011\Seeder\Events\EventHandlerInterface;
use Devian2011\Seeder\Events\EventInterface;
use Devian2011\Seeder\Loader\DatabaseLoader;
use Devian2011\Seeder\Output\OutputInterface;
use Devian2011\Seeder\SeederEvents;

class SeederProgressListener implements EventHandlerInterface
{
    private OutputInterface $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    public function getActions(): array
    {
        return [
            SeederEvents::EVENT_SEEDER_CONFIG_LOADED,
            SeederEvents::EVENT_SEEDER_START,
            SeederEvents::EVENT_SEEDER_DATA_LOADED,
            SeederEvents::EVENT_SEEDER_LOADING_DATA,
            SeederEvents::EVENT_SEEDER_SCHEMA_BUILT,
        ];
    }

    public function handle(EventInterface $event)
    {
        $this->output->write($event->getMessage());
    }
}
