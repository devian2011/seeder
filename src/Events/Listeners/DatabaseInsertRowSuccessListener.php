<?php

namespace Devian2011\Seeder\Events\Listeners;

use Devian2011\Seeder\Events\EventHandlerInterface;
use Devian2011\Seeder\Events\EventInsertionRowSuccess;
use Devian2011\Seeder\Events\EventInterface;
use Devian2011\Seeder\Loader\DatabaseLoader;
use Devian2011\Seeder\Output\OutputInterface;
use Devian2011\Seeder\SeederEvents;

class DatabaseInsertRowSuccessListener implements EventHandlerInterface
{
    private OutputInterface $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    public function getActions(): array
    {
        return [
            DatabaseLoader::EVENT_DATABASE_INSERT_SUCCESS,
        ];
    }

    /**
     * @param EventInsertionRowSuccess $event
     * @return void
     */
    public function handle(EventInterface $event)
    {
        $this->output->write(
            sprintf(
                "Data has been inserted to DB Table: %s.%s.\n SQL: %s.\n Data:%s\n",
                $event->getDatabase(), $event->getTable(), $event->getSql(), var_export($event->getData(), true)
            )
        );
    }
}
