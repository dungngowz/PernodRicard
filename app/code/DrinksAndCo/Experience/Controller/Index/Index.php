<?php

namespace DrinksAndCo\Experience\Controller\Index;

use Magento\Framework\Controller\ResultFactory;

class Index extends \Magento\Framework\App\Action\Action
{
    protected $experienceHelper;
    protected $resultForwardFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Store\Model\StoreManager $storeManager,
        \DrinksAndCo\Experience\Helper\Data $experienceHelper,
        \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory
    )
    {
        $this->experienceHelper = $experienceHelper;
        $this->resultForwardFactory = $resultForwardFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        if (!$this->experienceHelper->getConfig('general_settings/enabled')) {
            return $this->resultForwardFactory->create()->forward('noroute');
        }
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        if ($this->experienceHelper->getConfig('general_settings/template')) {
            $resultPage->getConfig()->setPageLayout($this->experienceHelper->getConfig('general_settings/template'));
        }
        return $resultPage;
    }
}
