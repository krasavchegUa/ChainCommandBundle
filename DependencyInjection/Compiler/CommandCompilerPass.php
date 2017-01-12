<?php
/*
 * This file is part of the krasavchegUa\ChainCommandBundle package.
 *
 * (c) Volodymyr Rudak <krasavcheg.ua@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace krasavchegUa\ChainCommandBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class CommandCompilerPass
 * @package krasavchegUa\ChainCommandBundle\DependencyInjection\Compiler
 */
class CommandCompilerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('chain_command_bundle.collection')) {
            return;
        }

        $definition = $container->findDefinition('chain_command_bundle.collection');
        $taggedServices = $container->findTaggedServiceIds('chain_command');

        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $tag) {
                if (!isset($tag['main'])) {
                    continue;
                }

                $definition->addMethodCall('addCommand', [new Reference($id), $tag['main']]);
            }
        }
    }
}