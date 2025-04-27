<?php
declare(strict_types=1);

namespace PeachCode\FPCWarmer\Model\ResourceModel\Queue\Item;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use PeachCode\FPCWarmer\Model\Queue\Item as QueueItem;
use PeachCode\FPCWarmer\Model\ResourceModel\Queue as QueueItemResource;

class Collection extends AbstractCollection
{
    protected function _construct(): void
    {
        $this->_init(QueueItem::class, QueueItemResource::class);
    }
}
