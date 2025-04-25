<?php
declare(strict_types=1);

namespace PeachCode\FPCWarmer\Cron;

use PeachCode\FPCWarmer\Logger\Logger;

class CronJob
{

    /**
     * Constructor
     *
     * @param Logger $handler
     */
    public function __construct(
        private readonly Logger $handler,
    ){}

    /**
     * Execute the cron
     *
     * @return void
     */
    public function generateQueue(): void
    {
        $this->handler->info("Cronjob CronJob is executed.");
    }

    /**
     * Execute the cron
     *
     * @return void
     */
    public function processQueue(): void
    {
        $this->handler->info("Cronjob CronJob is executed.");
    }
}

