<?php declare(strict_types=1);

namespace Mage2Kata\DiConfig\Model\Config;

class UnitConversionConfigConverter implements \Magento\Framework\Config\ConverterInterface
{
    /**
     * @inheritDoc
     */
    public function convert($document): array
    {
        $result = [];
        $rootElement = $this->getRootElement($document);
        foreach ($this->getChildrenByName($rootElement, 'unit') as $unitNode) {
            $unit = $unitNode->attributes->getNamedItem('id')->nodeValue;
            $conversions = $this->gatherConversions($unitNode);

            $result[$unit] = isset($result[$unit]) ? array_merge($result[$unit], $conversions) : $conversions;
        }

        return $result;
    }

    private function getRootElement(\DOMDocument $document): \DOMNode
    {
        return $this->getAllChildElements($document)[0];
    }

    /**
     * @return \DOMElement[]
     */
    private function getChildrenByName(\DOMElement $parent, string $name): array
    {
        return array_filter($this->getAllChildElements($parent), function (\DOMElement $child) use ($name) {
            return $name === $child->nodeName;
        });
    }

    /**
     * @return \DOMElement[]
     */
    private function getAllChildElements(\DOMNode $parent): array
    {
        return array_filter(iterator_to_array($parent->childNodes), function (\DOMNode $child) {
            return \XML_ELEMENT_NODE === $child->nodeType;
        });
    }

    /**
     * @return string[]
     */
    private function gatherConversions(\DOMElement $unitNode): array
    {
        $conversions = [];
        foreach ($this->getChildrenByName($unitNode, 'conversion') as $conversionNode) {
            $targetUnit = $conversionNode->attributes->getNamedItem('to')->nodeValue;
            $conversions[$targetUnit] = $conversionNode->attributes->getNamedItem('factor')->nodeValue;
        }
        return $conversions;
    }
}