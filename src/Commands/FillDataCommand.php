<?php

namespace Devian2011\Seeder\Commands;

use Devian2011\Seeder\Seeder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use function Webmozart\Assert\Tests\StaticAnalysis\null;

class FillDataCommand extends Command
{
    protected static $defaultName = "seeder:fill-data";

    protected function configure()
    {
        $this->addOption('templates-dir', 'td', InputOption::VALUE_REQUIRED, 'Path to templates dir')
            ->addOption('mode', 'm', InputOption::VALUE_REQUIRED, 'Mode - fake, test, predefined')
            ->addOption('params', 'p', InputOption::VALUE_REQUIRED, 'Path to .env files');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $templateDir = $input->getOption('templates-dir');
        $mode = $input->getOption('mode');
        $params = $input->getOption('params');
        try {
            $seeder = new Seeder($templateDir, $mode, explode(',', $params));
            $seeder->run();

            return self::SUCCESS;
        } catch (\Throwable $throwable) {
            $output->writeln($throwable->getMessage(), OutputInterface::OUTPUT_NORMAL);

            return self::FAILURE;
        }
    }
}
