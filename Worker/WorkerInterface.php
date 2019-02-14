<?php

namespace WorkerBundle\Worker;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Alexander Keil (alexanderkeil@leik-software.com)
 */
interface WorkerInterface
{
    public function run();

    public function getMessages(): array;

    public function canRun(array $options): bool;

    public function setInput(InputInterface $input): void;

    public function setOutput(OutputInterface $output): void;
}
