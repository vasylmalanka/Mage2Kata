<?php declare(strict_types=1);

namespace Mage2Kata\OrmEntity\Model;

use Magento\Framework\Model\AbstractModel;

class FloppyDisk extends AbstractModel
{
    public const SIZE = 'size';
    public const CAPACITY = 'capacity';
    public const BRAND = 'brand';
    public const COLOR = 'color';
    public const DATE_OF_MANUFACTURE = 'date_of_manufacture';

    protected function _construct()
    {
        $this->_init(\Mage2Kata\OrmEntity\Model\ResourceModel\FloppyDisk::class);
    }

    public function getSize()
    {
        return $this->getData(self::SIZE);
    }

    public function setSize($size): void
    {
        $this->setData(self::SIZE, $size);
    }

    public function getCapacity()
    {
        return $this->getData(self::CAPACITY);
    }

    public function setCapacity($capacity): void
    {
        $this->setData(self::CAPACITY, $capacity);
    }

    public function getBrand()
    {
        return $this->getData(self::BRAND);
    }

    public function setBrand($brand): void
    {
        $this->setData(self::BRAND, $brand);
    }

    public function getColor()
    {
        return $this->getData(self::COLOR);
    }

    public function setColor($color): void
    {
        $this->setData(self::COLOR, $color);
    }

    public function getDateOfManufacture()
    {
        return $this->getData(self::DATE_OF_MANUFACTURE);
    }

    public function setDateOfManufacture($dateOfManufacture): void
    {
        $this->setData(self::DATE_OF_MANUFACTURE, $dateOfManufacture);
    }
}
