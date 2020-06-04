<?php declare(strict_types=1);

namespace Mage2Kata\DiConfig;

use Magento\Framework\Config\GenericSchemaLocator;
use Magento\Framework\ObjectManager\ConfigInterface as ObjectManagerConfig;
use Magento\TestFramework\ObjectManager;
use PHPUnit\Framework\TestCase;

class DiConfigConfigurationTest extends TestCase
{
    /**
     * @var string
     */
    private $configType = Model\Config\UnitConversion\Virtual::class;

    /**
     * @var string
     */
    private $readerType = Model\Config\UnitConversion\Reader\Virtual::class;

    /**
     * @var string
     */
    private $schemaLocatorType = Model\Config\UnitConversion\SchemaLocator\Virtual::class;

    private function getDiConfig(): ObjectManagerConfig
    {
        return ObjectManager::getInstance()->get(ObjectManagerConfig::class);
    }

    private function assertDiArgumentsSame(string $expected, string $type, string $argumentName): void
    {
        $arguments = $this->getDiConfig()->getArguments($type);
        if (!isset($arguments[$argumentName])) {
            $this->fail(sprintf('No argument "%s" configured for "%s"', $argumentName, $type));
        }
        $this->assertSame($expected, $arguments[$argumentName]);
    }

    private function assertVirtualType(string $expected, string $type): void
    {
        $this->assertSame($expected, $this->getDiConfig()->getInstanceType($type));
    }

    private function assertDiArgumentType(string $expectedType, string $type, string $argumentName): void
    {
        $arguments = $this->getDiConfig()->getArguments($type);
        if (!isset($arguments[$argumentName])) {
            $this->fail(sprintf('No argument "%s" configured for "%s"', $argumentName, $type));
        }
        if (!isset($arguments[$argumentName]['instance'])) {
            $this->fail(sprintf('Argument "%s" for "%s" is not xsi:type="object"', $argumentName, $type));
        }
        $this->assertSame($expectedType, $arguments[$argumentName]['instance']);
    }

    public function testConfigDataVirtualType(): void
    {
        $this->assertVirtualType(\Magento\Framework\Config\Data::class, $this->configType);
        $this->assertDiArgumentsSame('mage2kata_unitconversion_map_config', $this->configType, 'cacheId');
        $this->assertDiArgumentType($this->readerType, $this->configType, 'reader');
    }

    public function testUnitConversionConfigReaderDiConfig(): void
    {
        $this->assertVirtualType(\Magento\Framework\Config\Reader\Filesystem::class, $this->readerType);
        $this->assertDiArgumentsSame('unit_conversion.xml', $this->readerType, 'fileName');
        $this->assertDiArgumentType($this->schemaLocatorType, $this->readerType, 'schemaLocator');
        $this->assertDiArgumentType($this->readerType, $this->configType, 'reader');
    }

    public function testUnitConversionConfigSchemaLocatorDiConfig(): void
    {
        $this->assertVirtualType(GenericSchemaLocator::class, $this->schemaLocatorType);
        $this->assertDiArgumentsSame('Mage2Kata_DiConfig', $this->schemaLocatorType, 'moduleName');
        $this->assertDiArgumentsSame('unit_conversion.xsd', $this->schemaLocatorType, 'schema');
    }

    public function testUnitConversionDataCanBeAccessed(): void
    {
        /** @var \Magento\Framework\Config\DataInterface $unitConversionConfig */
        $unitConversionConfig = ObjectManager::getInstance()->create($this->configType);
        $configData = $unitConversionConfig->get(null);
        $this->assertInternalType('array', $configData);
        $this->assertNotEmpty($configData);
    }
}