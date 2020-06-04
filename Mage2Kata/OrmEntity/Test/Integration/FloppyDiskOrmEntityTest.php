<?php declare(strict_types=1);

namespace Mage2Kata\OrmEntity;

use Mage2Kata\OrmEntity\Model\FloppyDisk;
use Mage2Kata\OrmEntity\Model\ResourceModel\FloppyDisk as FloppyDiskResource;
use Mage2Kata\OrmEntity\Model\ResourceModel\FloppyDisk\Collection as FloppyDiskCollection;
use Magento\TestFramework\ObjectManager;
use PHPUnit\Framework\TestCase;

/**
 * @magentoDbIsolation enabled
 */
class FloppyDiskOrmEntityTest extends TestCase
{
    private function createFloppy(): FloppyDisk
    {
        $floppy = $this->instantiateFloppy();
        $floppy->setBrand(uniqid('test-'));
        $floppy->setCapacity(mt_rand(280, 1288));
        $floppy->setDateOfManufacture(date('Y-m-d', mt_rand(strtotime('1971-01-01'), strtotime('1989-12-31'))));
        $floppy->setColor('#' . dechex(mt_rand(0, 255)) . dechex(mt_rand(0, 255)) . dechex(mt_rand(0, 255)));
        $sizes = ['8"', '5.25"', '3.5"', '3"'];
        $floppy->setSize($sizes[array_rand($sizes)]);
        $this->instantiateResourceModel()->save($floppy);
        return $floppy;
    }

    private function instantiateFloppy(): FloppyDisk
    {
        return ObjectManager::getInstance()->create(FloppyDisk::class);
    }

    private function instantiateResourceModel(): FloppyDiskResource
    {
        return ObjectManager::getInstance()->create(FloppyDiskResource::class);
    }

    public function testCanSaveAndLoad(): void
    {
        $floppy = $this->createFloppy();

        $floppyToLoad = $this->instantiateFloppy();
        $this->instantiateResourceModel()->load($floppyToLoad, $floppy->getId());

        $this->assertSame($floppy->getId(), $floppyToLoad->getId());
        $this->assertSame($floppy->getDateOfManufacture(), $floppyToLoad->getDateOfManufacture());
    }

    public function testCanLoadMultiplesFloppies(): void
    {
        $floppyA = $this->createFloppy();
        $floppyB = $this->createFloppy();

        /** @var FloppyDiskCollection $collection */
        $collection = ObjectManager::getInstance()->create(FloppyDiskCollection::class);
        $this->assertContains($floppyA->getId(), array_keys($collection->getItems()));
        $this->assertContains($floppyB->getId(), array_keys($collection->getItems()));
    }
}
