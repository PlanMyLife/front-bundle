<?php

namespace PlanMyLife\FrontBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('pml_front_generator');

        $rootNode
            ->children()
                ->scalarNode('engine')->end()
                ->arrayNode('path')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('src')->end()
                            ->scalarNode('name')->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}

