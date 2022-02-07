<?php

declare(strict_types=1);

namespace MakinaCorpus\Preferences\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class PreferencesConfiguration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('preferences');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->enumNode('caching_strategy')
                    ->values(['all', 'none'])
                    ->defaultNull()
                ->end()
                // Schema definition from configuration
                ->arrayNode('schema')
                    ->normalizeKeys(true)
                    ->prototype('array')
                        ->children()
                            // If null, then string
                            ->enumNode('type')
                                ->values(['string', 'bool', 'int', 'float'])
                                ->defaultNull()
                            ->end()
                            ->booleanNode('collection')->defaultFalse()->end()
                            // Default can be pretty much anything, even if type
                            // is different from what was exposed.
                            ->variableNode('default')->defaultNull()->end()
                            // Allowed values should probably be an array of values
                            // of the same type as upper, but you can put pretty
                            // much anything in it, validator will YOLO and accept
                            // anything that's in there.
                            ->variableNode('allowed_values')->defaultNull()->end()
                            ->scalarNode('label')->defaultNull()->end()
                            ->scalarNode('description')->defaultNull()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
