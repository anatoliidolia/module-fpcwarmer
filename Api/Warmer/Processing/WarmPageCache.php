<?php
declare(strict_types=1);

namespace PeachCode\FPCWarmer\Api\Warmer\Processing;

interface WarmPageCache
{
    /**
     * @return int
     */
    public function cacheGenerator(): int;
}
