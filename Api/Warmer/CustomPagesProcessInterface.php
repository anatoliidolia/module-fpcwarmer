<?php
declare(strict_types=1);

namespace PeachCode\FPCWarmer\Api\Warmer;

interface CustomPagesProcessInterface
{

    public function processCustomPages(array $urls): void;

}
