<?php

declare(strict_types=1);

namespace MakinaCorpus\Preferences\DependencyInjection;

use MakinaCorpus\Preferences\PreferencesSchema;
use MakinaCorpus\Preferences\Repository\CacheAllPreferencesRepository;
use MakinaCorpus\Preferences\Schema\ArrayPreferencesSchema;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

final class PreferencesExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(\dirname(__DIR__).'/Resources/config'));
        $loader->load('preferences.yaml');

        $bundles = $container->getParameter('kernel.bundles');
        if (\in_array('MakinaCorpus\\CoreBus\\Bridge\\Symfony\\CoreBusBundle', $bundles)) {
            $loader->load('handler.corebus.yaml');
        }

        if (isset($config['schema'])) {
            $schemaDefinition = new Definition();
            $schemaDefinition->setClass(ArrayPreferencesSchema::class);
            // In theory, our configuration was correctly registered, this should
            // work gracefully.
            $schemaDefinition->setArguments([$config['schema']]);
            $container->setDefinition('preferences.schema', $schemaDefinition);
            $container->setAlias(PreferencesSchema::class, 'preferences.schema');
        }

        if (isset($config['caching_strategy'])) {
            switch ($config['caching_strategy']) {

                case 'all':
                    $decoratorDefinition = new Definition();
                    $decoratorDefinition->setClass(CacheAllPreferencesRepository::class);
                    $decoratorDefinition->setDecoratedService('preferences.repository');
                    $decoratorDefinition->setArguments([new Reference('.inner')]);
                    $container->setDefinition('preferences.repository.decorator.cache', $decoratorDefinition);
                    break;

                case 'none':
                    // No cache.
                    break;

                default:
                    throw new InvalidArgumentException(\sprintf("Invalid preferences caching stragegy: '%s'", $config['caching_strategy']));
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getConfiguration(array $config, ContainerBuilder $container)
    {
        return new PreferencesConfiguration();
    }
}
