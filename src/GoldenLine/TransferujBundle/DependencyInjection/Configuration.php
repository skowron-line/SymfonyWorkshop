<?php

namespace GoldenLine\TransferujBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('transferuj');

        $rootNode
            ->children()
                ->scalarNode('id')
                    ->isRequired()
                ->end()
                ->scalarNode('secret')
                    ->isRequired()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
