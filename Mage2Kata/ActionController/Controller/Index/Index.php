<?php declare(strict_types=1);

namespace Mage2Kata\ActionController\Controller\Index;

use Mage2Kata\ActionController\Model\Exception\RequiredArgumentMissingException;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\AbstractResult;
use Magento\Framework\Controller\Result\Raw as RawResult;
use Magento\Framework\Controller\Result\RawFactory as RawResultFactory;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory as RedirectResultFactory;

class Index extends Action
{
    /**
     * @var RawResultFactory
     */
    private $rawResultFactory;

    /**
     * @var \UseCase
     */
    private $useCase;

    /**
     * @var RedirectResultFactory
     */
    private $redirectResultFactory;

    public function __construct(Context $context, RawResultFactory $rawResultFactory, \UseCase $useCase)
    {
        parent::__construct($context);
        $this->rawResultFactory = $rawResultFactory;
        $this->useCase = $useCase;
        $this->redirectResultFactory = $context->getResultRedirectFactory();
    }

    public function execute(): AbstractResult
    {
        return !$this->isPostRequest() ? $this->getMethodNotAllowedResult() : $this->processRequestAndRedirect();
    }

    /**
     * @return RawResult
     */
    private function processRequestAndRedirect(): AbstractResult
    {
        try {
            $this->useCase->doSomething($this->getRequest()->getParams());
        } catch (RequiredArgumentMissingException $exception) {
            return $this->getBadRequestResult();
        }

        $result = $this->getRedirectToHomepageResult();

        return $result;
    }

    private function getMethodNotAllowedResult(): AbstractResult
    {
        $result = $this->rawResultFactory->create();
        $result->setHttpResponseCode(405);

        return $result;
    }

    private function getBadRequestResult(): AbstractResult
    {
        $result = $this->rawResultFactory->create();
        $result->setHttpResponseCode(400);

        return $result;
    }

    private function isPostRequest(): bool
    {
        return 'POST' === $this->getRequest()->getMethod();
    }

    /**
     * @return Redirect
     */
    private function getRedirectToHomepageResult(): Redirect
    {
        $result = $this->redirectResultFactory->create();
        $result->setPath('/');
        return $result;
    }
}