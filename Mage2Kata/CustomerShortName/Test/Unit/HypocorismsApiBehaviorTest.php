<?php declare(strict_types=1);

namespace Mage2Kata\CustomerShortName;

use PHPUnit\Framework\TestCase;

class HypocorismsApiBehaviorTest extends TestCase
{
    private $apiUrl = 'http://hypocorisms.vinaikopp.com/names/';

    public function testReturnsJSON(): void
    {
        $response = file_get_contents($this->apiUrl);
        json_decode($response);
        $this->assertSame(
            \JSON_ERROR_NONE,
            json_last_error(),
            'JSON decode failed: ' . json_last_error_msg() . PHP_EOL . $response
        );
    }

    public function testReturnsEmptyArrayIfNoMatch(): void
    {
        $result = json_decode(file_get_contents($this->apiUrl . 'NOTANAME'), true);
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('data', $result);

        $this->assertInternalType('array', $result['data']);
        $this->assertArrayHasKey('hypocorisms', $result['data']);

        $this->assertInternalType('array', $result['data']['hypocorisms']);
        $this->assertEmpty($result['data']['hypocorisms']);
    }

    public function testReturnsArrayWithHypocorismsIfMatch(): void
    {
        $result = json_decode(file_get_contents($this->apiUrl . 'Robert'), true);
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('data', $result);

        $this->assertInternalType('array', $result['data']);
        $this->assertArrayHasKey('hypocorisms', $result['data']);

        $this->assertInternalType('array', $result['data']['hypocorisms']);

        $this->assertContains('Bob', $result['data']['hypocorisms']);
        $this->assertNotContains('Robert', $result['data']['hypocorisms']);
    }
}
