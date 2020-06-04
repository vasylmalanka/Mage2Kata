<?php declare(strict_types=1);

namespace Mage2Kata\OrmEntity\Model\ResourceModel\FloppyDisk;

use Mage2Kata\OrmEntity\Model\FloppyDisk as FloppyDiskModel;
use Mage2Kata\OrmEntity\Model\ResourceModel\FloppyDisk as FloppyDiskResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(FloppyDiskModel::class, FloppyDiskResource::class);
    }
}
