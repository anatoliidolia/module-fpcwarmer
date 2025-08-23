<?php
declare(strict_types=1);

namespace PeachCode\FPCWarmer\Model\Queue;

use Magento\Framework\Model\AbstractModel;
use PeachCode\FPCWarmer\Model\ResourceModel\Queue as ResourceModel;

class Item extends AbstractModel
{

    /**
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(ResourceModel::class);
    }
}
