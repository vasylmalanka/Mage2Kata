<?php declare(strict_types=1);

namespace Mage2Kata\DiConfig\Model\Config;

use Magento\Framework\Config\ConverterInterface;
use PHPUnit\Framework\TestCase;

class UnitConversionConfigConverterTest extends TestCase
{
    /**
     * @var UnitConversionConfigConverter
     */
    private $converter;

    private function createSource(string $xml): \DOMDocument
    {
        $source = new \DOMDocument();
        $source->loadXML($xml);
        return $source;
    }

    protected function setUp()
    {
        $this->converter = new UnitConversionConfigConverter();
    }

    public function testItCanBeInstantiated(): void
    {
        $this->assertInstanceOf(ConverterInterface::class, $this->converter);
    }

    public function testReturnsEmptyArrayForEmptyDocument(): void
    {
        $source = $this->createSource('<empty/>');
        $this->assertEquals([], $this->converter->convert($source));
    }

    public function testAddsEachFactorToConversionMapping(): void
    {
        $xml = <<<XML
<conversion_map>
    <unit id="g">
        <conversion to="mg" factor="111"/>
        <conversion to="kg" factor="222"/>
    </unit>
</conversion_map>
XML;
        $result = $this->converter->convert($this->createSource($xml));
        $this->assertSame('111', $result['g']['mg']);
        $this->assertSame('222', $result['g']['kg']);
    }

    public function testOverridesExistingConversionsDuringMerge(): void
    {
        $xml = <<<XML
<conversion_map>
    <unit id="g">
        <conversion to="mg" factor="111"/>
        <conversion to="kg" factor="222"/>
    </unit>
    <unit id="g">
        <conversion to="kg" factor="333"/>
    </unit>
</conversion_map>
XML;
        $result = $this->converter->convert($this->createSource($xml));
        $this->assertSame('111', $result['g']['mg']);
        $this->assertSame('333', $result['g']['kg']);
    }
}