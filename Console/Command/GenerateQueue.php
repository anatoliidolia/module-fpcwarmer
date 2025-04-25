<?php
declare(strict_types=1);

namespace PeachCode\FPCWarmer\Console\Command;

use PeachCode\FPCWarmer\Api\Warmer\GenerateQueueInterface;
use PeachCode\FPCWarmer\Logger\Logger;
use PeachCode\FPCWarmer\Model\Config\Data;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateQueue extends Command
{
    /**
     * @param Logger $handler
     * @param Data $config
     * @param GenerateQueueInterface $generateQueue
     * @param string|null $name
     */
    public function __construct(
        private readonly Logger                 $handler,
        private readonly Data                   $config,
        private readonly GenerateQueueInterface $generateQueue,
        ?string                                 $name = null
    ){
        parent::__construct($name);
    }

    /**
     * @inheritdoc
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        if($this->config->isEnable()){
            $this->handler->notice('Generates the queue for warmer');

            return $this->generateQueue->process();
        }
        $this->handler->notice('Please Enable Module for Generates the queue for warmer');
        $output->writeln("Please Enable Module");
        return Command::FAILURE;
    }

    /**
     * @inheritdoc
     */
    protected function configure(): void
    {
        $this->setName("peachcode:fpcwarmer:generatequeue");
        $this->setDescription("Generates the queue for warmer.");
        parent::configure();
    }
}

