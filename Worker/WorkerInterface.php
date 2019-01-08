<?php

namespace WorkerBundle\Worker;

/**
 * @author Alexander Keil (alexanderkeil@leik-software.com)
 */
interface WorkerInterface
{
    public function run();

    public function getMessages(): array;

    public function canRun(array $options): bool;
}
