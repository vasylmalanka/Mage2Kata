<?php declare(strict_types=1);

namespace Mage2Kata\EventObserver\Setup;

use Magento\Catalog\Model\Product;
use Magento\Eav\Setup\EavSetup;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;

class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var EavSetup
     */
    private $eavSetup;

    public function __construct(
        EavSetup $eavSetup
    ) {
        $this->eavSetup = $eavSetup;
    }

    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context): void
    {
        $this->eavSetup->addAttribute(Product::ENTITY, 'magento_se_points', [
            'label' => 'Magento SE Points Value',
            'type' => 'int',
            'required' => 0,
            'user_defined' => 1,
            'comparable' => 1,
            'visible_on_frontend' => 1,
            'is_configurable' => 0,
            'group' => 'Product Details',
        ]);
    }
}
