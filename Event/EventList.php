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

/**
 * Class EventList
 * @package krasavchegUa\ChainCommandBundle\Event
 */
final class EventList
{
    /**
     * On chain start
     */
    const STARTED = 'chain_command.start';

    /**
     * On registering children commands
     */
    const CHILD_REGISTERED = 'chain_command.child_registered';

    /**
     * On before execution main command
     */
    const BEFORE_MAIN = 'chain_command.before_main';

    /**
     * On after execution main command
     */
    const AFTER_MAIN = 'chain_command.after_main';

    /**
     * On before execution chain of children commands
     */
    const BEFORE_CHAIN = 'chain_command.before_chain';

    /**
     * On after execution child/slave command
     */
    const AFTER_CHILD = 'chain_command.after_child';

    /**
     * On chain finish
     */
    const FINISHED = 'chain_command.finished';
}