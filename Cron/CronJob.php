<?php
declare(strict_types=1);

namespace PeachCode\FPCWarmer\Cron;

use PeachCode\FPCWarmer\Api\Warmer\Processing\WarmPageCache;
use PeachCode\FPCWarmer\Api\Warmer\Queue\GenerateQueueInterface;
use PeachCode\FPCWarmer\Logger\Logger;
use PeachCode\FPCWarmer\Model\Config\Data;

class CronJob
{

    /**
     * Constructor
     *
     * @param Data $config
     * @param GenerateQueueInterface $generateQueue
     * @param WarmPageCache $warmPageCache
     * @param Logger $handler
     */
    public function __construct(
        private readonly Data                   $config,
        private readonly GenerateQueueInterface $generateQueue,
        private readonly WarmPageCache $warmPageCache,
        private readonly Logger $handler
    ){}

    /**
     * Execute the cron
     *
     * @return void
     */
    public function generateQueue(): void
    {
        if(!$this->config->isEnable()) {
            return;
        }

        $this->handler->info("Cronjob CronJob is executed.");
        $this->generateQueue->process();

    }

    /**
     * Execute the cron
     *
     * @return void
     */
    public function processQueue(): void
    {
        if(!$this->config->isEnable()) {
            return;
        }

        $this->handler->info("Cronjob CronJob is executed.");
        $this->warmPageCache->cacheGenerator();
    }
}

