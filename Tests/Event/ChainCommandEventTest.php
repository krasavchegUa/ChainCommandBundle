<?php
/*
 * This file is part of the krasavchegUa\ChainCommandBundle package.
 *
 * (c) Volodymyr Rudak <krasavcheg.ua@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace krasavchegUa\ChainCommandBundle\Tests\Event;

use krasavchegUa\ChainCommandBundle\Event\ChainCommandEvent;
use Symfony\Component\Console\Command\HelpCommand;
use Symfony\Component\Console\Command\ListCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

class ChainCommandEventTest extends \PHPUnit_Framework_TestCase
{
    public function testCommandEquals()
    {
        $command = new HelpCommand();
        $event = new ChainCommandEvent(new HelpCommand(), new ArrayInput([]), new BufferedOutput());
        $this->assertEquals($command, $event->getCommand());
    }

    public function testCommandNotEquals()
    {
        $event = new ChainCommandEvent(new HelpCommand(), new ArrayInput([]), new BufferedOutput());
        $this->assertNotEquals(new ListCommand(), $event->getCommand());
    }

    public function testMainAndChildCommandEquals()
    {
        $command = new HelpCommand();
        $child = new ListCommand();

        $event = new ChainCommandEvent(new HelpCommand(), new ArrayInput([]), new BufferedOutput(), new ListCommand());
        $this->assertEquals($command, $event->getCommand());
        $this->assertEquals($child, $event->getChild());
    }
}