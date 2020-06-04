<?php declare(strict_types=1);

namespace Mage2Kata\ActionController\Test\Unit\Controller\Index;

use Mage2Kata\ActionController\Controller\Index\Index;
use Mage2Kata\ActionController\Model\Exception\RequiredArgumentMissingException;
use Magento\Framework\App\Action\Context as ActionContext;
use Magento\Framework\Controller\Result\Redirect as RedirectResult;
use Magento\Framework\Controller\Result\Raw as RawResult;
use Magento\Framework\Controller\Result\RawFactory as RawResultFactory;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\HTTP\PhpEnvironment\Request as HttpRequest;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class IndexTest extends TestCase
{
    /**
     * @var Index
     */
    private $controller;

    /**
     * @var RawResult|MockObject
     */
    private $mockRawResult;

    /**
     * @var HttpRequest|MockObject
     */
    private $mockRequest;

    /**
     * @var \UseCase|MockObject
     */
    private $mockUseCase;

    /**
     * @var RedirectResult|MockObject
     */
    private $mockRedirectResult;

    protected function setUp()
    {
        $this->mockRawResult = $this->getMockBuilder(RawResult::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockRequest = $this->getMockBuilder(HttpRequest::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockUseCase = $this->getMockBuilder(\UseCase::class)
            ->setMethods(['doSomething'])
            ->getMock();

        $this->mockRedirectResult = $this->getMockBuilder(RedirectResult::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var RawResultFactory|MockObject $mockRawResultFactory */
        $mockRawResultFactory = $this->getMockBuilder(RawResultFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $mockRawResultFactory->method('create')->willReturn($this->mockRawResult);

        /** @var RedirectFactory|MockObject $mockRedirectResultFactory */
        $mockRedirectResultFactory = $this->getMockBuilder(RedirectFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $mockRedirectResultFactory->method('create')->willReturn($this->mockRedirectResult);

        /** @var ActionContext|MockObject $mockContext */
        $mockContext = $this->getMockBuilder(ActionContext::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockContext->method('getRequest')->willReturn($this->mockRequest);
        $mockContext->method('getResultRedirectFactory')->willReturn($mockRedirectResultFactory);

        $this->controller = new Index($mockContext, $mockRawResultFactory, $this->mockUseCase);
    }

    public function testReturnsResultInstance(): void
    {
        $this->mockRequest->method('getMethod')->willReturn('POST');
        $this->assertInstanceOf(ResultInterface::class, $this->controller->execute());
    }

    public function testReturns405MethodNotAllowedForNonPostRequest(): void
    {
        $this->mockRequest->method('getMethod')->willReturn('GET');
        $this->mockRawResult->expects($this->once())->method('setHttpResponseCode')->with(405);
        $this->controller->execute();
    }

    public function testReturns400BadRequestIfRequiredArgumentsAreMissing(): void
    {
        $incompleteArguments = [];
        $this->mockRequest->method('getMethod')->willReturn('POST');
        $this->mockRequest->method('getParams')->willReturn($incompleteArguments);

        $this->mockUseCase
            ->expects($this->once())
            ->method('doSomething')
            ->with($incompleteArguments)
            ->willThrowException(new RequiredArgumentMissingException('Test exception: required arguments are missing'));

        $this->mockRawResult->expects($this->once())->method('setHttpResponseCode')->with(400);

        $this->controller->execute();
    }

    public function testRedirectsToHomepageIfRequestWasProcessed(): void
    {
        $this->mockRequest->method('getMethod')->willReturn('POST');
        $this->mockRequest->method('getParams')->willReturn(['foo_id' => 123]);

        $this->mockRedirectResult->expects($this->once())->method('setPath');

        $this->assertSame($this->mockRedirectResult, $this->controller->execute());
    }
}