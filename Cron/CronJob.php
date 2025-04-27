<?php
declare(strict_types=1);

namespace PeachCode\FPCWarmer\Cron;

use PeachCode\FPCWarmer\Api\Warmer\Processing\WarmPageCache;
use PeachCode\FPCWarmer\Api\Warmer\Queue\GenerateQueueInterface;
use PeachCode\FPCWarmer\Logger\Logger;

class CronJob
{

    /**
     * Constructor
     *
     * @param GenerateQueueInterface $generateQueue
     * @param WarmPageCache $warmPageCache
     * @param Logger $handler
     */
    public function __construct(
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
        $this->handler->info("Cronjob CronJob is executed.");
        $this->warmPageCache->cacheGenerator();
    }
}

