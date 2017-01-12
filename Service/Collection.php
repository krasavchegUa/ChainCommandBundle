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

use Symfony\Component\Console\Command\Command;

/** Used to hold all chained commands
 *
 * Class Collection
 * @package krasavchegUa\ChainCommandBundle\Service
 */
class Collection
{
    /** array of chains
     *
     * @var array
     */
    private $commands = [];

    /** Validate and push command to chain
     *
     * @param Command $command
     * @param $main
     */
    public function addCommand(Command $command, $main)
    {
        $name = $command->getName();

        if (!$this->canBeAdded($name)) {
            return;
        }

        $this->commands[$main][] = [
            'name' => $name,
            'command' => $command,
        ];
    }

    /** Check if command is main/master in chain
     *
     * @param $name
     * @return bool
     */
    public function isMain($name)
    {
        if (array_key_exists($name, $this->commands)) {
            return true;
        }

        return false;
    }

    /** Check if command is child/slave of the main/master command in chain
     *
     * @param $name
     * @return bool
     */
    public function isChild($name)
    {
        foreach ($this->commands as $main => $children) {
            foreach ($children as $child) {
                if ($child['name'] == $name) {
                    return true;
                }
            }
        }

        return false;
    }

    /** Retrieve main/master command name by providing child/slave name
     *
     * @param $name
     * @return bool|int|string
     */
    public function getMainChainName($name)
    {
        foreach ($this->commands as $main => $children) {
            foreach ($children as $child) {
                if ($child['name'] == $name) {
                    return $main;
                }
            }
        }

        return false;
    }

    /** Get children for main/master command
     *
     * @param $name
     * @return array|mixed
     */
    public function getChildren($name)
    {
        if ($this->isMain($name)) {
            return $this->commands[$name];
        }

        return [];
    }

    /** Validate if command is already in chain
     *
     * @param $name
     * @return bool
     */
    private function canBeAdded($name)
    {
        return !$this->isMain($name) && !$this->isChild($name);
    }
}