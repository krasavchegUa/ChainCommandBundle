<?php

namespace krasavchegUa\ChainCommandBundle\Tests\Service;

use AppKernel;
use krasavchegUa\ChainCommandBundle\Tests\Fixtures\Command\GreetingCommand;
use krasavchegUa\ChainCommandBundle\Tests\Fixtures\Command\HelloCommand;
use krasavchegUa\ChainCommandBundle\Tests\Fixtures\Command\YoCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Input\ArrayInput;

class RunnerTest extends KernelTestCase
{
    private $app;
    private $output;

    protected function setUp()
    {
        static::bootKernel();
        $this->app = new Application(static::$kernel);
        $this->app->setAutoExit(false);
        $this->output = new BufferedOutput();
    }

    public function testChain()
    {
        $helloCommand = new HelloCommand();
        $greetingCommand = new GreetingCommand();
        $yoCommand = new YoCommand();

        $this->app->add($helloCommand);
        $this->app->add($greetingCommand);
        $this->app->add($yoCommand);

        $collection = static::$kernel->getContainer()->get('chain_command_bundle.collection');
        $collection->addCommand($greetingCommand, $helloCommand->getName());
        $collection->addCommand($yoCommand, $helloCommand->getName());

        $exitCode = $this->app->run(new ArrayInput([$helloCommand->getName()]), $this->output);
        $this->assertEquals(113, $exitCode); // command was interrupted and started again to make a chain
        $this->assertEquals($this->output->fetch(), "Hi from Bar!\nGreetings from Bar!\nYo from Bar!\n");
    }
}