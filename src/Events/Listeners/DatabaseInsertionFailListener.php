<?php

namespace Devian2011\Seeder\Events\Listeners;

use Devian2011\Seeder\Events\EventHandlerInterface;
use Devian2011\Seeder\Events\EventInterface;
use Devian2011\Seeder\Loader\DatabaseLoader;
use Devian2011\Seeder\Output\OutputInterface;

class DatabaseInsertionFailListener implements EventHandlerInterface
{
    private OutputInterface $output;

    /**
     * @param OutputInterface $output
     */
    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    public function getActions(): array
    {
        return [
            DatabaseLoader::EVENT_DATABASE_LOAD_ERROR
        ];
    }

    public function handle(EventInterface $event)
    {
        $this->output->write(
            sprintf(
                "Error on insert data to table: %s.%s. Error: %s. Sql: %s. Data: %s",
                $event->getContext()['database'],
                $event->getContext()['table'],
                $event->getContext()['message'],
                $event->getContext()['sql'],
                json_decode($event->getContext()['data'])
            )
        );
    }

}
