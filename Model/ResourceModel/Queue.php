<?php
declare(strict_types=1);

namespace PeachCode\FPCWarmer\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Queue extends AbstractDb
{
    public const TABLE_NAME = 'fpc_queue';
    public const CART_ID = 'entity_id';

    /**
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(self::TABLE_NAME,self::CART_ID);
    }
}
