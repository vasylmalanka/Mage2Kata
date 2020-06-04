<?php declare(strict_types=1);

namespace Mage2Kata\EventObserver\Observer;

use Magento\Catalog\Model\Product;
use Magento\Framework\Api\AttributeInterface;
use Magento\Framework\Event\Observer as Event;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Model\Quote\Item as QuoteItem;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CheckoutCartProductAddAfterObserverTest extends TestCase
{
    public function testImplementsObserverInterface(): void
    {
        $this->assertInstanceOf(ObserverInterface::class, new CheckoutCartProductAddAfterObserver());
    }

    public function testCopiesMagentoSEPointsToQuoteItem(): void
    {
        /** @var Product|MockObject $mockProduct */
        $mockProduct = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->getMock();
        /** @var QuoteItem|MockObject $mockQuoteItem */
        $mockQuoteItem = $this->getMockBuilder(QuoteItem::class)
            ->disableOriginalConstructor()
            ->getMock();
        /** @var AttributeInterface|MockObject $mockAttribute */
        $mockAttribute = $this->getMockBuilder(AttributeInterface::class)
            ->getMock();
        $mockAttribute->method('getValue')->willReturn(42);
        $mockProduct->method('getCustomAttribute')->with('magento_se_points')->willReturn($mockAttribute);
        $mockQuoteItem->expects($this->once())->method('setData')->with('magento_se_points', 42);

        /** @var Event|MockObject $mockEvent */
        $mockEvent = $this->getMockBuilder(Event::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockEvent->method('getData')->willReturnMap([
            ['product', null, $mockProduct],
            ['quote_item', null, $mockQuoteItem],
        ]);

        (new CheckoutCartProductAddAfterObserver())->execute($mockEvent);
    }
}
