<?php declare(strict_types=1);

namespace Mage2Kata\CustomerShortName\Plugin;

use Mage2Kata\CustomerShortName\Api\ShortenFirstNameInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Helper\View as CustomerViewHelper;

class CustomerViewHelperShortNamePlugin
{
    /**
     * @var ShortenFirstNameInterface
     */
    private $shortenFirstName;

    public function __construct(ShortenFirstNameInterface $shortenFirstName)
    {
        $this->shortenFirstName = $shortenFirstName;
    }

    public function aroundGetCustomerName(
        CustomerViewHelper $subject,
        callable $proceed,
        CustomerInterface $customerDataModel
    ): string {
        $originalFirstname = $customerDataModel->getFirstname();
        $customerDataModel->setFirstname($this->shortenFirstName->shorten($originalFirstname));
        $resultName = $proceed($customerDataModel);
        $customerDataModel->setFirstname($originalFirstname);

        return $resultName;
    }
}