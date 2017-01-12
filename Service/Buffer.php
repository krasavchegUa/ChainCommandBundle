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

use Symfony\Component\Console\Output\Output;

/**
 * Class Buffer
 * @package krasavchegUa\ChainCommandBundle\Service
 */
class Buffer extends Output
{
    /**
     * @var string
     */
    private $text = '';

    /** Return buffered output form console command and Purge buffer
     *
     * @return string
     */
    public function flush()
    {
        $content = $this->text;
        $this->clean();

        return $content;
    }

    /** Retrieve buffered output from console command
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Purge buffer
     */
    public function clean()
    {
        $this->text = '';
    }

    /** Add to buffer
     *
     * @param string $message
     * @param bool $newline
     */
    protected function doWrite($message, $newline)
    {
        $this->text .= $message;

        if ($newline) {
            $this->text .= "\n";
        }
    }
}