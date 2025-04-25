<?php
declare(strict_types=1);

namespace PeachCode\FPCWarmer\Api\Warmer;

interface GenerateQueueInterface
{
    /**
     * @return int
     */
    public function process(): int;
}
