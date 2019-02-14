<?php

namespace WorkerBundle\Worker;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Alexander Keil (alexanderkeil@leik-software.com)
 */
abstract class BaseWorker implements WorkerInterface
{
    protected $messages=[];
    
    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var InputInterface
     */
    protected $input;

    public function getMessages(): array
    {
        return $this->messages;
    }

    public function setOutput(OutputInterface $output): void
    {
        $this->output = $output;
    }

    public function setInput(InputInterface $input): void
    {
        $this->input = $input;
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
