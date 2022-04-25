<?php

namespace Devian2011\Seeder\Events;

class Event implements EventInterface
{
    private string $message;
    private array $context;

    public function __construct(string $message, array $context = [])
    {
        $this->message = $message;
        $this->context = $context;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getContext(): array
    {
        return $this->context;
    }

}
