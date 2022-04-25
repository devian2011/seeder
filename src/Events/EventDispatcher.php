<?php

namespace Devian2011\Seeder\Events;

class EventDispatcher implements EventsDispatcherInterface
{
    /** @var EventHandlerInterface[] */
    private array $actions = [];

    public function notify(string $action, EventInterface $event)
    {
        if (!isset($this->actions[$action])) return;
        foreach ($this->actions[$action] as $handler) {
            $handler->handle($event);
        }
    }

    public function subscribe(EventHandlerInterface $eventListener)
    {
        foreach ($eventListener->getActions() as $action){
            $this->actions[$action][] = $eventListener;
        }
    }

}
