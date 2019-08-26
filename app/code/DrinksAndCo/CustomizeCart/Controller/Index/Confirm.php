<?php

namespace DrinksAndCo\CustomizeCart\Controller\Index;

class Confirm extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;
    protected $_commonHelper;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \DrinksAndCo\Base\Helper\Common $commonHelper
    ){
        $this->_pageFactory = $pageFactory;
        $this->_commonHelper = $commonHelper;
        return parent::__construct($context);
    }

    public function execute()
    {
        $resultPage = $this->_pageFactory->create();
        $resultPage->addHandle('customizecart_index_confirm');

        $this->_commonHelper->setTitlePage($resultPage->getConfig(), 'Information Confirm');

        return $resultPage;
    }
}