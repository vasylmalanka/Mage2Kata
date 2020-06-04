<?php declare(strict_types=1);

namespace Mage2Kata\EventObserver\Test\Integration;

use Mage2Kata\EventObserver\Observer\CheckoutCartProductAddAfterObserver;
use Magento\Framework\Event\ConfigInterface as EventConfig;
use Magento\TestFramework\ObjectManager;
use PHPUnit\Framework\TestCase;

class CheckoutCartAddProductAfterObserverConfigTest extends TestCase
{
    public function testCheckoutCartAddProductAfterObserverIsConfigured(): void
    {
        /** @var EventConfig $observerConfiguration */
        $observerConfiguration = ObjectManager::getInstance()->create(EventConfig::class);
        $observers = $observerConfiguration->getObservers('checkout_cart_product_add_after');

        $this->assertArrayHasKey('mage2kata_eventobserver', $observers);
        $this->assertSame(
            ltrim(CheckoutCartProductAddAfterObserver::class, '\\'),
            $observers['mage2kata_eventobserver']['instance']
        );
    }
}
