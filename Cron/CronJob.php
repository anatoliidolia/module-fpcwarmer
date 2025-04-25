<?php
declare(strict_types=1);

namespace PeachCode\FPCWarmer\Cron;

use Psr\Log\LoggerInterface;

class CronJob
{

    /**
     * Constructor
     *
     * @param LoggerInterface $logger
     */
    public function __construct(private LoggerInterface $logger)
    {

    }

    /**
     * Execute the cron
     *
     * @return void
     */
    public function execute(): void
    {
        $this->logger->info("Cronjob CronJob is executed.");
    }
}

