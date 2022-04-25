<?php

namespace Devian2011\Seeder\Events;

interface EventsDispatcherInterface
{
    public function notify(string $action, EventInterface $event);
    public function subscribe(EventHandlerInterface $eventListener);
}
