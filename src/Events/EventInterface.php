<?php

namespace Devian2011\Seeder\Events;

interface EventInterface
{
    public function getMessage();

    public function getContext(): array;
}
