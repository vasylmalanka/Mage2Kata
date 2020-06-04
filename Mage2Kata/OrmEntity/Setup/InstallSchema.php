<?php declare(strict_types=1);

namespace Mage2Kata\OrmEntity\Setup;

use Mage2Kata\OrmEntity\Model\FloppyDisk as FloppyDiskModel;
use Mage2Kata\OrmEntity\Model\ResourceModel\FloppyDisk as FloppyDiskResource;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{

    /**
     * @inheritDoc
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $tableName = $setup->getTable(FloppyDiskResource::TABLE);
        $table = $setup->getConnection()->newTable($tableName);
        $table->addColumn(FloppyDiskResource::ID_FIELD, Table::TYPE_INTEGER, null, [
            'unsigned' => true,
            'primary' => true,
            'identity' => true,
            'nullable' => false,
        ]);
        $table->addColumn(FloppyDiskModel::SIZE, Table::TYPE_TEXT, 10, [
            'nullable' => false,
        ]);
        $table->addColumn(FloppyDiskModel::CAPACITY, Table::TYPE_INTEGER, null, [
            'nullable' => false,
            'unsigned' => true,
        ]);
        $table->addColumn(FloppyDiskModel::BRAND, Table::TYPE_TEXT, 255, [
            'nullable' => false,
        ]);
        $table->addColumn(FloppyDiskModel::COLOR, Table::TYPE_TEXT, 255, [
            'nullable' => false,
        ]);
        $table->addColumn(FloppyDiskModel::DATE_OF_MANUFACTURE, Table::TYPE_DATE, null, [
            'nullable' => true,
        ]);
        $setup->getConnection()->createTable($table);
    }
}
