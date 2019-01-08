<?php

namespace WorkerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;


/**
 * @author Alexander Keil (alexanderkeil@leik-software.com)
 */
class WorkerCompilerPass implements CompilerPassInterface
{

    public function process(ContainerBuilder $container)
    {

        if(!$container->has('worker_bundle.command.worker')){
            return;
        }
        $definition = $container->findDefinition('worker_bundle.command.worker');
        $taggedWorkers = $container->findTaggedServiceIds('rawburner.worker_bundle.worker');
        foreach ($taggedWorkers as $id => $worker){
            $definition->addMethodCall('addWorker', [new Reference($id)]);
        }

    }
}
