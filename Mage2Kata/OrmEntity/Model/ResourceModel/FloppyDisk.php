<?php declare(strict_types=1);

namespace Mage2Kata\OrmEntity\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class FloppyDisk extends AbstractDb
{
    public const TABLE = 'mage2kata_floppy_disk';
    public const ID_FIELD = 'id';

    protected function _construct()
    {
        $this->_init(self::TABLE, self::ID_FIELD);
    }
}
