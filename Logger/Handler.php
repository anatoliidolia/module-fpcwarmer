<?php
declare(strict_types=1);

namespace PeachCode\FPCWarmer\Logger;

use Magento\Framework\Logger\Handler\Base;

class Handler extends Base
{
    /**
     * File name
     * @var string
     */
    protected $fileName = '/var/log/fpcwarmer.log';
}
