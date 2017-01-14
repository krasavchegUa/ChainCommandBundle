<?php
/*
 * This file is part of the krasavchegUa\ChainCommandBundle package.
 *
 * (c) Volodymyr Rudak <krasavcheg.ua@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace krasavchegUa\ChainCommandBundle\Tests\Service;

use krasavchegUa\ChainCommandBundle\Service\Collection;
use Symfony\Bundle\FrameworkBundle\Command\ServerRunCommand;
use Symfony\Bundle\FrameworkBundle\Command\ServerStopCommand;
use Symfony\Component\Console\Command\HelpCommand;


class CollectionTest extends \PHPUnit_Framework_TestCase
{
    protected $collection;

    public function setUp()
    {
        $this->collection = new Collection();
        $this->collection->addCommand(new HelpCommand(), 'list');
    }

    public function testIfMainCommand()
    {
        $this->assertTrue($this->collection->isMain('list'));
        $this->assertFalse($this->collection->isMain('help'));
    }

    public function testIfChildCommand()
    {
        $this->assertFalse($this->collection->isChild('list'));
        $this->assertTrue($this->collection->isChild('help'));
    }

    public function testMainCommandName()
    {
        $this->assertEquals('list', $this->collection->getMainName('help'));
        $this->assertFalse($this->collection->getMainName('list'));
    }

    public function testChildCommands()
    {
        $helpCommand = new HelpCommand();
        $runCommand = new ServerRunCommand();
        $stopCommand = new ServerStopCommand();

        $collection = $this->collection;
        $collection->addCommand(new ServerRunCommand(), 'list');
        $collection->addCommand(new ServerStopCommand(), 'list');

        $result = $collection->getChildren('list');
        $fullList = [
            ['name' => 'help', 'command' => $helpCommand],
            ['name' => 'server:run', 'command' => $runCommand],
            ['name' => 'server:stop', 'command' => $stopCommand],
        ];

        $notFullList = array_slice($fullList, 1);

        $this->assertEquals($fullList, $result);
        $this->assertNotEquals($notFullList, $result);
    }
}