<?php

namespace ARV\BlogBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 * Class Configuration
 * To learn more see
 *   {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 * @package ARV\BlogBundle\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('arv_blog');

        $supportedEditors = array('none', 'tinymce');

        $rootNode
            ->children()
                ->scalarNode('user_class')->defaultNull()->end()
                ->booleanNode('is_secure')->defaultFalse()->end()
                ->arrayNode('article')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('content_editor')
                            ->validate()
                                ->ifNotInArray($supportedEditors)
                                ->thenInvalid('The editor %s is not supported. '
                                    . 'Please choose one of ' . json_encode($supportedEditors))
                            ->end()
                            ->defaultValue('tinymce')
                            ->cannotBeEmpty()
                        ->end()
                        ->booleanNode('need_validation')
                            ->defaultTrue()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('comment')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->integerNode('waiting_time')
                            ->min(0)
                            ->defaultValue(5)
                            ->cannotBeEmpty()
                        ->end()
                        ->booleanNode('display_email')
                            ->defaultTrue()
                        ->end()
                        ->booleanNode('write_as_anonymous')
                            ->defaultFalse()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
