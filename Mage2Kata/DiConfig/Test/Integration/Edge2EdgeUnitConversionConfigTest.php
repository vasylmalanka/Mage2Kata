<?php declare(strict_types=1);

namespace Mage2Kata\DiConfig\Test\Integration;

use Mage2Kata\DiConfig\Model\Config\UnitConversion\Reader\Virtual as UnitConversioReader;
use Mage2Kata\DiConfig\Model\Config\UnitConversion\Virtual as UnitConversionConfig;
use Magento\Framework\Config\CacheInterface;
use Magento\Framework\Config\Data as Config;
use Magento\Framework\Config\FileResolverInterface;
use Magento\Framework\Config\Reader\Filesystem;
use Magento\TestFramework\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;

class Edge2EdgeUnitConversionConfigTest extends \PHPUnit\Framework\TestCase
{
    private $configType = UnitConversionConfig::class;

    public function testCanAccessUnitConversionConfig(): void
    {
        $objectManager = ObjectManager::getInstance();
        /** @var Config $config */
        $config = $objectManager->create($this->configType);
        $this->assertSame('2.20462257811', $config->get('kg/lbs'));
    }

    public function testMultipleFilesCanBeMerged(): void
    {
        /** @var MockObject|FileResolverInterface $mockFileResolver */
        $mockFileResolver = $this->getMockBuilder(FileResolverInterface::class)
            ->getMock();
        $mockFileResolver->method('get')->willReturn([
            'test1.xml' => <<<XML
<conversion_map>
    <unit id="kg" type="weight">
        <conversion to="mg" factor="111"/>
        <conversion to="g" factor="222"/>
        <conversion to="lbs" factor="333"/>
    </unit>
</conversion_map>
XML
            , 'test2.xml' => <<<XML
<conversion_map>
    <unit id="kg" type="weight">
        <conversion to="lbs" factor="444"/>
    </unit>
</conversion_map>
XML
        ]);
        $objectManager = ObjectManager::getInstance();
        $reader = $objectManager->create(
            UnitConversioReader::class,
            ['fileResolver' => $mockFileResolver]
        );
        $mockCache = $this->getMockBuilder(CacheInterface::class)
            ->getMock();
        $mockCache->method('load')->willReturn(false);
        $config = $objectManager->create($this->configType, ['reader' => $reader, 'cache' => $mockCache]);
        $this->assertSame('444', $config->get('kg/lbs'));
    }
}