<?php declare(strict_types=1);

namespace Mage2Kata\EventObserver\Test\Integration;

use Magento\Catalog\Model\Product;
use Magento\Framework\Event\Manager as EventManager;
use Magento\Quote\Model\Quote\Item as QuoteItem;
use Magento\TestFramework\ObjectManager;

class ObserverCopiesProductAttributeToQuoteItemTest extends \PHPUnit\Framework\TestCase
{
    public function testObserverCopiesMagentoSEPointsFromProductToQuoteItem(): void
    {
        /** @var Product $product */
        $product = ObjectManager::getInstance()->create(Product::class);
        $product->setCustomAttribute('magento_se_points', 500);

        /** @var QuoteItem $quoteItem */
        $quoteItem = ObjectManager::getInstance()->create(QuoteItem::class);

        /** @var EventManager $eventManager */
        $eventManager = ObjectManager::getInstance()->create(EventManager::class);

        $eventManager->dispatch(
            'checkout_cart_product_add_after',
            ['quote_item' => $quoteItem, 'product' => $product]
        );

        $this->assertSame(500, $quoteItem->getData('magento_se_points'));
    }
}
