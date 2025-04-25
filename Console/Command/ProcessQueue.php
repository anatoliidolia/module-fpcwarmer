<?php
declare(strict_types=1);

namespace PeachCode\FPCWarmer\Console\Command;

use PeachCode\FPCWarmer\Logger\Logger;
use PeachCode\FPCWarmer\Model\Config\Data;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProcessQueue extends Command
{

    /**
     * @param Logger $handler
     * @param Data $config
     * @param string|null $name
     */
    public function __construct(
        private readonly Logger $handler,
        private readonly Data $config,
        ?string               $name = null
    ){
        parent::__construct($name);
    }

    /**
     * @inheritdoc
     */
    protected function execute(
        InputInterface  $input,
        OutputInterface $output
    ): int
    {
        if ($this->config->isEnable()) {
            $this->handler->notice('Processing of the batch of URLs');

            return Command::SUCCESS;
        }

        $this->handler->notice('Please Enable Module for Processing of the batch of URLs');
        $output->writeln("Please Enable Module");
        return Command::FAILURE;
    }

    /**
     * @inheritdoc
     */
    protected function configure(): void
    {
        $this->setName("peachcode:fpcwarmer:processqueue");
        $this->setDescription("Processing of the batch of URLs.");
        parent::configure();
    }
}

