<?php declare(strict_types=1);

namespace Mage2Kata\ActionController\Controller\Index;

use Magento\TestFramework\TestCase\AbstractController;

class CreateIntegrationTest extends AbstractController
{
    public function testCanHandleGetRequests(): void
    {
        $this->getRequest()->setMethod('GET');
        $this->dispatch('mage2kata_actioncontroller/index/create');
        $this->assertSame(200, $this->getResponse()->getHttpResponseCode());
        $this->assertContains('<body ', $this->getResponse()->getBody());
    }

    public function testCanNotHandlePostRequest(): void
    {
        $this->getRequest()->setMethod('POST');
        $this->dispatch('mage2kata_actioncontroller/index/create');
        $this->assertSame(404, $this->getResponse()->getHttpResponseCode());
        $this->assert404NotFound();
    }
}