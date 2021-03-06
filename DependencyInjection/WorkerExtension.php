<?php

namespace WorkerBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use WorkerBundle\Worker\WorkerInterface;

/**
 * @author Alexander Keil (alexanderkeil@leik-software.com)
 */
class WorkerExtension extends Extension
{

    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
        $container
            ->registerForAutoconfiguration(WorkerInterface::class)
            ->addTag('rawburner.worker_bundle.worker');
    }
}
