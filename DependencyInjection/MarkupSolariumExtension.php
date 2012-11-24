<?php

namespace Markup\SolariumBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class MarkupSolariumExtension extends Extension
{
    /**
     * {@inheritdoc}
     **/
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);

        $defaultClient = $config['default_client'];
        if (!count($config['clients'])) {
            $config['clients'][$defaultClient] = array();
        } elseif (count($config['clients']) === 1) {
            $defaultClient = key($config['clients']);
        }

        foreach ($config['clients'] as $name => $client_options) {
            $client_name = sprintf('solarium.client.%s', $name);
            $adapter_name = sprintf('solarium.client.adapter.%s', $name);

            if (isset($client_options['client_class'])) {
                $client_class = $client_options['client_class'];
                unset($client_options['client_class']);
            } else {
                $client_class = 'Solarium\Client';
            }

            if (isset($client_options['adapter_class'])) {
                $adapter_class = $client_options['adapter_class'];
                unset($client_options['adapter_class']);
            } else {
                $adapter_class = 'Solarium\Core\Client\Adapter\Curl';
            }

            $clientDefinition = new Definition($client_class);
            $container->setDefinition($client_name, $clientDefinition);

            if ($name == $defaultClient) {
                $container->setAlias('solarium.client', $client_name);
            }

            $container
                ->setDefinition($adapter_name, new Definition($adapter_class))
                ->setArguments(array($name => $client_options));

            $adapter = new Reference($adapter_name);
            $container->getDefinition($client_name)->addMethodCall('setAdapter', array($adapter));
        }
    }
}
