<?php
/*
 * This file is part of the krasavchegUa\ChainCommandBundle package.
 *
 * (c) Volodymyr Rudak <krasavcheg.ua@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace krasavchegUa\ChainCommandBundle\Event;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ChainCommandEvent
 * @package krasavchegUa\ChainCommandBundle\Event
 */
class ChainCommandEvent extends ConsoleCommandEvent
{
    /**
     * @var Command
     */
    private $child;

    /**
     * ChainCommandEvent constructor.
     * @param Command $main
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param Command|null $child
     */

    public function __construct(
        Command $main,
        InputInterface $input,
        OutputInterface $output,
        Command $child = null
    ) {
        parent::__construct($main, $input, $output);

        $this->child = $child;
    }

    /** Return child/slave command
     *
     * @return null|Command
     */
    public function getChild()
    {
        return $this->child;
    }
}