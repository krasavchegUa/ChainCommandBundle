<?php
/*
 * This file is part of the krasavchegUa\ChainCommandBundle package.
 *
 * (c) Volodymyr Rudak <krasavcheg.ua@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace krasavchegUa\ChainCommandBundle\Service;

use krasavchegUa\ChainCommandBundle\Event\ChainCommandEvent;
use krasavchegUa\ChainCommandBundle\Event\EventList;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/** Runs chain of commands
 *
 * Class Runner
 * @package krasavchegUa\ChainCommandBundle\Service
 */
class Runner
{
    /**
     * @var Collection
     */
    private $collection;

    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * @var
     */
    private $input;

    /** Console output
     *
     * @var
     */
    private $output;

    /**
     * Runner constructor.
     * @param Collection $collection
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(Collection $collection, EventDispatcherInterface $eventDispatcher)
    {
        $this->collection = $collection;
        $this->eventDispatcher = $eventDispatcher;
    }

    /** If command is main/master run a chain
     *  If a child/slave halt execution and output error to user
     *
     * @param ConsoleCommandEvent $event
     */
    public function launch(ConsoleCommandEvent $event)
    {
        $command = $event->getCommand();
        $name = $command->getName();

        if ($this->collection->isMain($name)) {
            $event->disableCommand();

            $this->input = $event->getInput();
            $this->output = $event->getOutput();

            $this->chain($command);
        } elseif ($this->collection->isChild($name)) {
            $event->disableCommand();
            $event->stopPropagation();

            $event->getOutput()->writeln(
                sprintf(
                    'Error: %s command is a member of %s command chain and cannot be executed on its own.',
                    $name,
                    $this->collection->getMainName($name)
                )
            );
        }
    }

    /** Command is main/master - lets run a chain execution
     *
     * @param Command $command
     */
    public function chain(Command $command)
    {
        $this->fireEvent(EventList::STARTED, $command);

        $children = $this->registerChildren($command);

        $this->main($command);
        $this->children($command, $children);
    }

    /** Return a list of children for main/master command
     *
     * @param Command $main
     * @return array|mixed
     */
    private function registerChildren(Command $main)
    {
        $children = $this->collection->getChildren($main->getName());

        foreach ($children as $child) {
            $this->fireEvent(
                EventList::CHILD_REGISTERED,
                $main,
                $child['command']
            );
        }

        return $children;
    }

    /** Run main/master command itself
     *
     * @param Command $command
     */
    private function main(Command $command)
    {
        $this->fireEvent(EventList::BEFORE_MAIN, $command);
        $buffedOutput = $this->runCommand($command, $this->input);
        $this->fireEvent(EventList::AFTER_MAIN, $command, null, $buffedOutput);
    }

    /** Run every child command through loop
     *
     * @param Command $command
     * @param array $children
     */
    private function children(Command $command, array $children)
    {
        $this->fireEvent(EventList::BEFORE_CHAIN, $command);

        foreach ($children as $child) {
            /** @var Command $childCommand */
            $childCommand = $child['command'];
            $buffedOutput = $this->runCommand($childCommand, $this->getArrayInput());
            $this->fireEvent(EventList::AFTER_CHILD, $command, $childCommand, $buffedOutput);
        }

        $this->fireEvent(EventList::FINISHED, $command);
    }

    /** Run single command
     *
     * @param Command $command
     * @param $input
     * @return BufferedOutput
     */
    private function runCommand(Command $command, $input)
    {
        $buffer = new BufferedOutput();
        $command->run($input, $buffer);

        $output = $buffer->fetch();
        $buffer->write($output);
        $this->output->write($output);

        return $buffer;
    }

    /**
     * @return ArrayInput
     */
    private function getArrayInput()
    {
        return new ArrayInput([]);
    }

    /** Run event dispatcher
     *
     * @param $name
     * @param $command
     * @param null $childCommand
     * @param null $input
     * @param null $output
     */
    private function fireEvent($name, $command, $childCommand = null, $output = null, $input = null)
    {
        $this->eventDispatcher->dispatch(
            $name,
            new ChainCommandEvent(
                $command,
                $input ? $input : $this->input,
                $output ? $output : $this->output,
                $childCommand
            )
        );
    }
}