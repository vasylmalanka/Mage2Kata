<?php declare(strict_types=1);

namespace Mage2Kata\CustomerShortName\Model;

use Mage2Kata\CustomerShortName\Api\ShortenFirstNameInterface;
use Magento\Framework\HTTP\ClientFactory as HttpClientFactory;
use Magento\Framework\HTTP\ClientInterface as HttpClient;
use PHPUnit\Framework\MockObject\MockObject;

class HypocorismsApiShortensNameTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var HttpClient|MockObject
     */
    private $mockHttpClient;

    private function assertShortName(string $expected, string $firstname): void
    {
        /** @var HttpClientFactory|MockObject $mockHttpClientFactory */
        $mockHttpClientFactory = $this->getMockBuilder(HttpClientFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockHttpClientFactory->method('create')->willReturn($this->mockHttpClient);

        $this->assertSame($expected, (new HypocorismsApiShortensName($mockHttpClientFactory))->shorten($firstname));
    }

    public function invalidApiResponseDataProvider(): array
    {
        return [
            [''],
            [null],
            [false],
            [[]],
            [json_encode([])],
            [json_encode(['data' => ''])],
            [json_encode(['data' => []])],
            [json_encode(['data' => ['hypocorisms' => '']])],
            [json_encode(['data' => ['hypocorisms' => []]])],
        ];
    }

    protected function setUp()
    {
        $this->mockHttpClient = $this->getMockBuilder(HttpClient::class)->getMock();
    }

    public function testImplementsTheShortenFirstNameInterface(): void
    {
        /** @var HttpClientFactory|MockObject $mockHttpClientFactory */
        $mockHttpClientFactory = $this->getMockBuilder(HttpClientFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->assertInstanceOf(
            ShortenFirstNameInterface::class,
            new HypocorismsApiShortensName($mockHttpClientFactory)
        );
    }

    /**
     * @param mixed $invalidResponse
     * @dataProvider invalidApiResponseDataProvider
     */
    public function testReturnsSpecifiedFirstnameIfResponseDoesNotContainShortName($invalidResponse): void
    {
        $this->mockHttpClient->expects($this->once())->method('getBody')->willReturn($invalidResponse);
        $this->assertShortName('Foo', 'Foo');
    }

    public function testReturnsFirstShortNameFromResponse(): void
    {
        $this->mockHttpClient->expects($this->once())->method('getBody')->willReturn(json_encode([
            'data' => ['hypocorisms' => ['Bar', 'Baz']]
        ]));
        $this->assertShortName('Bar', 'Foo');
    }
}
