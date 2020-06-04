<?php declare(strict_types=1);

namespace Mage2Kata\Interceptor\Test\Integration;

use Mage2Kata\Interceptor\Model\ExternalCustomerApi;
use Mage2Kata\Interceptor\Plugin\CustomerRepositoryPlugin;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\App\Area;
use Magento\Framework\Exception\LocalizedException;
use Magento\TestFramework\App\State as AppAreaState;
use Magento\TestFramework\Interception\PluginList;
use Magento\TestFramework\ObjectManager;
use PHPUnit\Framework\TestCase;

class CustomerRepositoryPluginTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var string
     */
    private $moduleId = 'mage2kata_interceptor';

    /**
     * @param string|null $areaCode
     * @throws LocalizedException
     */
    private function setArea(?string $areaCode): void
    {
        /** @var AppAreaState $appArea */
        $appArea = $this->objectManager->create(AppAreaState::class);
        $appArea->setAreaCode($areaCode);
    }

    private function getCustomerRepositoryPluginInfo(): array
    {
        /** @var PluginList $pluginList */
        $pluginList = $this->objectManager->create(PluginList::class);

        return $pluginList->get(CustomerRepositoryInterface::class, []);
    }

    protected function setUp(): void
    {
        $this->objectManager = ObjectManager::getInstance();
    }

    protected function tearDown(): void
    {
        $this->setArea(null);
    }

    /**
     * @throws LocalizedException
     */
    public function testTheModuleInterceptsCallsToTheCustomerRepositoryInWebapiRestScope(): void
    {
        $this->setArea(Area::AREA_WEBAPI_REST);
        $pluginInfo = $this->getCustomerRepositoryPluginInfo();

        $this->assertSame(CustomerRepositoryPlugin::class, $pluginInfo[$this->moduleId]['instance']);
    }

    /**
     * @throws LocalizedException
     */
    public function testTheModuleDoNotInterceptCallsToTheCustomerRepositoryInGlobalScope(): void
    {
        $this->setArea(Area::AREA_GLOBAL);

        $this->assertArrayNotHasKey($this->moduleId, $this->getCustomerRepositoryPluginInfo());
    }

    /**
     * @magentoDataFixture Magento/Customer/_files/customer.php
     */
    public function testTheExternalCustomerApiIsCalledWhenNewCustomerIsSaved(): void
    {
        $this->setArea(Area::AREA_WEBAPI_REST);

        $mockExternalApi = $this->getMockBuilder(ExternalCustomerApi::class)
            ->setMethods(['registerNewCustomer'])
            ->getMock();
        $mockExternalApi->expects($this->once())->method('registerNewCustomer');
        $this->objectManager->configure([ExternalCustomerApi::class => ['shared' => true]]);
        $this->objectManager->addSharedInstance($mockExternalApi, ExternalCustomerApi::class);

        /** @var CustomerRepositoryInterface $repository */
        $repository = $this->objectManager->create(CustomerRepositoryInterface::class);

        $customer = $repository->get('customer@example.com');
        $customer->setId(null);
        $customer->setEmail('alice@example.com');
        $customer->setFirstname('Alice');

        $repository->save($customer);
    }
}