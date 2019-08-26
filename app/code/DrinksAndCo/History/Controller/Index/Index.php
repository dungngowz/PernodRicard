<?php

namespace DrinksAndCo\History\Controller\Index;

use Magento\Framework\Controller\ResultFactory;

class Index extends \Magento\Framework\App\Action\Action
{
    protected $historyHelper;
    protected $resultForwardFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Store\Model\StoreManager $storeManager,
        \DrinksAndCo\History\Helper\Data $historyHelper,
        \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory
    )
    {
        $this->historyHelper = $historyHelper;
        $this->resultForwardFactory = $resultForwardFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        if (!$this->historyHelper->getConfig('general_settings/enabled')) {
            return $this->resultForwardFactory->create()->forward('noroute');
        }
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        if ($this->historyHelper->getConfig('general_settings/template')) {
            $resultPage->getConfig()->setPageLayout($this->historyHelper->getConfig('general_settings/template'));
        }
        return $resultPage;
    }
}
