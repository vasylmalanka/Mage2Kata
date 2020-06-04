<?php

namespace Mage2Kata\CustomerShortName\Model;

use Mage2Kata\CustomerShortName\Api\ShortenFirstNameInterface;
use Magento\Framework\HTTP\ClientFactory as HttpClientFactory;

class HypocorismsApiShortensName implements ShortenFirstNameInterface
{
    /**
     * @var HttpClientFactory
     */
    private $httpClientFactory;

    public function __construct(HttpClientFactory $httpClientFactory)
    {
        $this->httpClientFactory = $httpClientFactory;
    }

    public function shorten(string $firstname): string
    {
        $httpClient = $this->httpClientFactory->create();
        $result = @json_decode($httpClient->getBody(), true);
        if ($this->hasHypocorism($result)) {
            return $result['data']['hypocorisms'][0];
        }

        return $firstname;
    }

    /**
     * @param mixed $result
     */
    private function hasHypocorism($result): bool
    {
        return is_array($result)
            && isset($result['data'])
            && is_array($result['data'])
            && isset($result['data']['hypocorisms'])
            && is_array($result['data']['hypocorisms'])
            && count($result['data']['hypocorisms']) > 0;
    }
}
