<?php

namespace Devian2011\Seeder\Events;

class ExceptionEvent implements EventInterface
{
    private \Exception $exception;

    public function __construct(\Exception $exception)
    {
        $this->exception = $exception;
    }

    public function getMessage(): string
    {
        return $this->exception->getMessage();
    }

    public function getContext(): array
    {
        return [
            'line' => $this->exception->getLine(),
            'file' => $this->exception->getFile()
        ];
    }
}
