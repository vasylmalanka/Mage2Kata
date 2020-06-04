<?php declare(strict_types=1);

namespace Mage2Kata\ActionController\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\AbstractResult;
use Magento\Framework\Controller\Result\Forward;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Create extends Action
{
    /**
     * @var PageFactory
     */
    private $pageFactory;

    /**
     * @var ForwardFactory
     */
    private $forwardFactory;

    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        ForwardFactory $forwardFactory
    ) {
        parent::__construct($context);
        $this->pageFactory = $pageFactory;
        $this->forwardFactory = $forwardFactory;
    }

    public function execute(): AbstractResult
    {
        return $this->isGetRequest()
            ? $this->handleGetRequest()
            : $this->handleNonGetRequest();
    }

    private function handleGetRequest(): Page
    {
        return $this->pageFactory->create();
    }

    private function handleNonGetRequest(): Forward
    {
        $forward = $this->forwardFactory->create();
        $forward->forward('noroute');

        return $forward;
    }

    private function isGetRequest(): bool
    {
        return 'GET' === $this->getRequest()->getMethod();
    }
}