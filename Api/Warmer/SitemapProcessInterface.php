<?php
declare(strict_types=1);

namespace PeachCode\FPCWarmer\Api\Warmer;

interface SitemapProcessInterface
{

    public function sitemapProcess(array $urls): void;

}
