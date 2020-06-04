<?php declare(strict_types=1);

namespace Mage2Kata\Interceptor\Plugin;

use Mage2Kata\Interceptor\Model\ExternalCustomerApi;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;

class CustomerRepositoryPlugin
{
    /**
     * @var ExternalCustomerApi
     */
    private $customerApi;

    public function __construct(ExternalCustomerApi $customerApi)
    {
        $this->customerApi = $customerApi;
    }

    public function aroundSave(
        CustomerRepositoryInterface $subject,
        callable $proceed,
        CustomerInterface $customer,
        string $passwordHash = null
    ): CustomerInterface {
        $isCustomerNew = null === $customer->getId();
        /** @var CustomerInterface $savedCustomer */
        $savedCustomer = $proceed($customer, $passwordHash);
        if ($isCustomerNew) {
            $this->customerApi->registerNewCustomer($savedCustomer->getId());
        }

        return $savedCustomer;
    }
}