<?php

namespace WorkerBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use WorkerBundle\Worker\WorkerInterface;
use Symfony\Component\Console\Input\InputOption;

/**
 * @author Alexander Keil (alexanderkeil@leik-software.com)
 */
class WorkerCommand extends Command
{
    use LockableTrait;

    protected $workers = [];

    public function addWorker(WorkerInterface $worker): void
    {
        $this->workers[] = $worker;
    }

    protected function configure(): void
    {
        $this
            ->setName('rb:worker:run')
            ->setDescription('runs all enabled worker')
            ->addOption(
                'incl',
                null,
                InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
                'List of workers to run. Example --incl=SomeWorker. Multiple options allowed'
            )
            ->addOption(
                'excl',
                null,
                InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
                'List of workers to skip. Example --excl=MyWorker. Multiple options allowed'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        if(!$this->lock()){
            $output->writeln('The command is already running in another process.');

            return 0;
        }
        /** @var WorkerInterface $worker */
        foreach ($this->workers as $worker) {
            $worker->setInput($input);
            $worker->setOutput($output);
            if (!$worker->canRun($input->getOptions())) {
                $output->writeln(sprintf('Worker "%s" cannot run', \get_class($worker)));
                continue;
            }
            $output->writeln(sprintf('Running Worker "%s"', \get_class($worker)));

            try {
                $worker->run();
                foreach ($worker->getMessages() as $message) {
                    $output->writeln($message);
                }
            } catch (\Throwable $exception) {
                $output->writeln(
                    sprintf('Exception in Worker "%s": "%s" in File "%s" on %d', \get_class($worker), $exception->getMessage(), $exception->getFile(), $exception->getLine())
                );
            }finally{
                $this->release();
            }
        }
    }
}
