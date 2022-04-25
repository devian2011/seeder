<?php

namespace Devian2011\Seeder\Events;

interface EventHandlerInterface
{
    /**
     * @return string[]
     */
    public function getActions(): array;

    public function handle(EventInterface $event);
}
