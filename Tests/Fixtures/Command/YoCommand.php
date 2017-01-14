<?php

namespace krasavchegUa\ChainCommandBundle\Tests\Fixtures\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class YoCommand extends Command
{
    public function configure()
    {
        $this->setName('chaintest:yo');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Yo from Bar!');
    }
}
