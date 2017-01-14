<?php
/*
 * This file is part of the krasavchegUa\ChainCommandBundle package.
 *
 * (c) Volodymyr Rudak <krasavcheg.ua@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace krasavchegUa\ChainCommandBundle\Listener;

use krasavchegUa\ChainCommandBundle\Event\ChainCommandEvent;
use krasavchegUa\ChainCommandBundle\Event\EventList;
use krasavchegUa\ChainCommandBundle\Service\Runner;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/** Listen for events in application
 *
 * Class CommandListener
 * @package krasavchegUa\ChainCommandBundle\Listener
 */
class CommandListener implements EventSubscriberInterface
{
    /**
     * @var Runner
     */
    private $runner;

    /**
     * @var LoggerInterface
     */
    private $logger;


    /**
     * CommandListener constructor.
     * @param LoggerInterface $logger
     * @param Runner $runner
     */
    public function __construct(LoggerInterface $logger, Runner $runner)
    {
        $this->logger = $logger;
        $this->runner = $runner;
    }

    /** List of events
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            ConsoleEvents::COMMAND => 'console',
            EventList::STARTED => 'start',
            EventList::CHILD_REGISTERED => 'childRegistered',
            EventList::BEFORE_MAIN => 'beforeMain',
            EventList::AFTER_MAIN => 'afterMain',
            EventList::BEFORE_CHAIN => 'beforeChildChain',
            EventList::AFTER_CHILD => 'afterChild',
            EventList::FINISHED => 'finished',
        ];
    }

    /** If someone runs a console command - call Runner. It knows what to do next
     *
     * @param ConsoleCommandEvent $event
     */
    public function console(ConsoleCommandEvent $event)
    {
        if (!$event->isPropagationStopped()) {
            $this->runner->launch($event);
        }
    }

    /** Chain execution started. Log main command name
     *
     * @param ChainCommandEvent $event
     */
    public function start(ChainCommandEvent $event)
    {
        $this->log(sprintf('%s is a master command of a command chain that has registered member commands',
            $event->getCommand()->getName()
        ));
    }

    /** Log names of children commands
     *
     * @param ChainCommandEvent $event
     */
    public function childRegistered(ChainCommandEvent $event)
    {
        $this->log(sprintf('%s registered as a member of %s command chain',
            $event->getChild()->getName(),
            $event->getCommand()->getName()
        ));
    }

    /** Log before main command execution
     *
     * @param ChainCommandEvent $event
     */
    public function beforeMain(ChainCommandEvent $event)
    {
        $this->log(sprintf('Executing %s command itself first:',
            $event->getCommand()->getName()
        ));
    }

    /** Main command executed. Log command output
     *
     * @param ChainCommandEvent $event
     */
    public function afterMain(ChainCommandEvent $event)
    {
        $this->log($event->getOutput()->fetch());
    }

    /** Prepare to run chain members
     *
     * @param ChainCommandEvent $event
     */
    public function beforeChildChain(ChainCommandEvent $event)
    {
        $this->log(sprintf('Executing %s chain members:',
            $event->getCommand()->getName()
        ));
    }

    /** Child/slave command executed. Log command output
     *
     * @param ChainCommandEvent $event
     */
    public function afterChild(ChainCommandEvent $event)
    {
        $this->log($event->getOutput()->fetch());
    }

    /** Chain execution has ended.
     *
     * @param ChainCommandEvent $event
     */
    public function finished(ChainCommandEvent $event)
    {
        $this->log(sprintf('Execution of %s chain completed.',
            $event->getCommand()->getName()
        ));
    }

    /** Save log to file
     *
     * @param $text
     */
    private function log($text)
    {
        $this->logger->info($text);
    }
}