<?php

namespace WorkerBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use WorkerBundle\Command\WorkerCommand;
use WorkerBundle\DependencyInjection\Compiler\WorkerCompilerPass;

/**
 * @author Alexander Keil (alexanderkeil@leik-software.com)
 */
class WorkerBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new WorkerCompilerPass());
    }

}
