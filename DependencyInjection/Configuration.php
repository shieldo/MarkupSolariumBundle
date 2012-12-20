<?php

namespace Markup\SolariumBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
* Configuration for bundle
*/
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     **/
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('markup_solarium');

        $rootNode
            ->children()
                ->scalarNode('default_client')->cannotBeEmpty()->defaultValue('default')->end()
                ->arrayNode('clients')
                    ->canBeUnset()
                    ->useAttributeAsKey('key', false)
                    ->prototype('array')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('client_class')->cannotBeEmpty()->defaultValue('Solarium\Client')->end()
                            ->scalarNode('adapter_class')->cannotBeEmpty()->defaultValue('Solarium\Core\Client\Adapter\Curl')->end()
                            ->scalarNode('host')->defaultValue('127.0.0.1')->end()
                            ->scalarNode('port')->defaultValue(8983)->end()
                            ->scalarNode('path')->defaultValue('/solr')->end()
                            ->scalarNode('timeout')->defaultValue(5)->end()
                            ->scalarNode('core')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
