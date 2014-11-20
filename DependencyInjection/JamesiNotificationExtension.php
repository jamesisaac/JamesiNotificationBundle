<?php

namespace Jamesi\NotificationBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class JamesiNotificationExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $container->setParameter('jamesi_notification.config', $config);

        $container->setParameter('jamesi_notification.alert_class', $config['alert_class']);
        $container->setParameter('jamesi_notification.notifier_class', $config['notifier_class']);

        $container->setParameter('jamesi_notification.from_email.address', $config['from_email']['address']);
        $container->setParameter('jamesi_notification.from_email.sender_name', $config['from_email']['sender_name']);
    }
}
