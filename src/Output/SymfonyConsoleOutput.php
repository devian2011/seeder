<?php

namespace Devian2011\Seeder\Output;

class SymfonyConsoleOutput implements OutputInterface
{
    private \Symfony\Component\Console\Output\OutputInterface $output;

    public function __construct(\Symfony\Component\Console\Output\OutputInterface $output)
    {
        $this->output = $output;
    }

    public function write(string $message)
    {
        $this->output->writeln($message);
    }
}
