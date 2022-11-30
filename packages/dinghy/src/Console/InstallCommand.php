<?php

namespace Integrity\Dinghy\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Integrity\Dinghy\Install;

// the name of the command is what users type after "php bin/console"
#[AsCommand(name: 'inflate')]
class InstallCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $install = new Install();

        $install->execute();

        return Command::SUCCESS;
    }
}