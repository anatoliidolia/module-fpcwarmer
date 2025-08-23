<?php
declare(strict_types=1);

namespace PeachCode\FPCWarmer\Console\Command;

use PeachCode\FPCWarmer\Logger\Logger;
use PeachCode\FPCWarmer\Model\Config\Data;
use PeachCode\FPCWarmer\Api\Warmer\Processing\WarmPageCache;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProcessQueue extends Command
{

    /**
     * @param Logger $handler
     * @param Data $config
     * @param WarmPageCache $warmPageCache
     * @param string|null $name
     */
    public function __construct(
        private readonly Logger $handler,
        private readonly Data $config,
        private readonly WarmPageCache $warmPageCache,
        ?string               $name = null
    ){
        parent::__construct($name);
    }

    /**
     * Warm FPC
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(
        InputInterface  $input,
        OutputInterface $output
    ): int
    {
        if ($this->config->isEnable()) {
            $this->handler->notice('Processing of the batch of URLs');

            return $this->warmPageCache->cacheGenerator();
        }

        $this->handler->notice('Please Enable Module for Processing of the batch of URLs');
        $output->writeln("Please Enable Module");
        return Command::FAILURE;
    }

    /**
     * CLI Details
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->setName("peachcode:fpcwarmer:processqueue");
        $this->setDescription("Processing of the batch of URLs.");
        parent::configure();
    }
}

