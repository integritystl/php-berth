<?php

namespace Integrity\Dinghy\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Integrity\Dinghy\Configuration;

// the name of the command is what users type after "php bin/console"
#[AsCommand(name: 'config:generate')]
class GenerateConfigurationCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $configuration = new Configuration();

        $configuration->generate();

        return Command::SUCCESS;
    }
}