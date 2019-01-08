<?php

namespace WorkerBundle\Worker;

/**
 * @author Alexander Keil (alexanderkeil@leik-software.com)
 */
abstract class BaseWorker implements WorkerInterface
{
    protected $messages=[];

    public function getMessages(): array
    {
        return $this->messages;
    }

    public function canRun(array $options=[]): bool
    {
        if (0 === \count($options['incl']) && 0 === \count($options['excl'])) {
            return true;
        }
        $namespace = \get_class($this);
        $path = explode('\\', $namespace);
        $currentWorker = array_pop($path);
        if (\count($options['excl']) > 0 && \in_array($currentWorker, $options['excl'], true)) {
            return false;
        }
        if (\count($options['incl']) > 0 && !\in_array($currentWorker, $options['incl'], true)) {
            return false;
        }

        return true;
    }
}
