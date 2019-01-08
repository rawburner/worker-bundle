<?php

namespace WorkerBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use WorkerBundle\Command\WorkerCommand;
use WorkerBundle\Worker\WorkerInterface;

/**
 * @author Alexander Keil (alexanderkeil@leik-software.com)
 */
class WorkerBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container
            ->registerForAutoconfiguration(WorkerInterface::class)
            ->addTag('rawburner.worker_bundle.worker');

        if(!$container->has(WorkerCommand::class)){
            return;
        }
        $definition = $container->findDefinition(WorkerCommand::class);
        $taggedWorkers = $container->findTaggedServiceIds('rawburner.worker_bundle.worker');
        foreach ($taggedWorkers as $id => $worker){
            $definition->addMethodCall('addWorker', [new Reference($id)]);
        }
    }

}
