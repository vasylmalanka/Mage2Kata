<?php declare(strict_types=1);

namespace Mage2Kata\Interceptor\Test\Unit\Plugin;

use Mage2Kata\Interceptor\Model\ExternalCustomerApi;
use Mage2Kata\Interceptor\Plugin\CustomerRepositoryPlugin;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CustomerRepositoryPluginTest extends TestCase
{
    /**
     * @var CustomerRepositoryPlugin
     */
    private $plugin;

    /**
     * @var MockObject|CustomerRepositoryInterface
     */
    private $mockCustomerRepository;

    /**
     * @var MockObject|CustomerInterface
     */
    private $mockCustomerToBeSave;

    /**
     * @var MockObject|CustomerInterface
     */
    private $mockSavedCustomer;
    /**
     * @var MockObject|ExternalCustomerApi
     */
    private $mockExternalCustomerApi;

    public function __invoke(CustomerInterface $customer, ?string $passwordHash): CustomerInterface
    {
        return $this->mockSavedCustomer;
    }

    private function callAroundSavePlugin(): CustomerInterface
    {
        return $this->plugin->aroundSave($this->mockCustomerRepository, $this, $this->mockCustomerToBeSave, null);
    }

    protected function setUp(): void
    {
        $this->mockCustomerRepository = $this->getMockBuilder(CustomerRepositoryInterface::class)->getMock();
        $this->mockCustomerToBeSave = $this->getMockBuilder(CustomerInterface::class)->getMock();
        $this->mockSavedCustomer = $this->getMockBuilder(CustomerInterface::class)->getMock();

        $this->mockExternalCustomerApi = $this->getMockBuilder(ExternalCustomerApi::class)
            ->setMethods(['registerNewCustomer'])
            ->getMock();
        $this->plugin = new CustomerRepositoryPlugin($this->mockExternalCustomerApi);
    }

    public function testAroundSaveMethodCanBeCalled(): void
    {
        $this->assertSame($this->mockSavedCustomer, $this->callAroundSavePlugin());
    }

    public function testItNotifiesExternalApiForNewCustomer(): void
    {
        $customerId = 123;
        $this->mockCustomerToBeSave->method('getId')->willReturn(null);
        $this->mockSavedCustomer->method('getId')->willReturn($customerId);
        $this->mockExternalCustomerApi
            ->expects($this->once())
            ->method('registerNewCustomer')
            ->with($customerId);
        $this->callAroundSavePlugin();
    }

    public function testItDoesNotNotifyExternalApiForExistingCustomer(): void
    {
        $this->mockCustomerToBeSave->method('getId')->willReturn(23);
        $this->mockExternalCustomerApi->expects($this->never())->method('registerNewCustomer');
        $this->callAroundSavePlugin();
    }
}