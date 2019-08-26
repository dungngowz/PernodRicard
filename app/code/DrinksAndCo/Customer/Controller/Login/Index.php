<?php

namespace DrinksAndCo\Customer\Controller\Login;

use DrinksAndCo\Customer\Helper\Email;

class Index extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;
    protected $helperEmail;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \DrinksAndCo\Base\Helper\Common $commonHelper,
        Email $helperEmail
    ){
        $this->_pageFactory = $pageFactory;
        $this->helperEmail = $helperEmail;
        $this->commonHelper = $commonHelper;
        return parent::__construct($context);
    }

    public function execute()
    {
        $resultPage = $this->_pageFactory->create();
        $resultPage->addHandle('customer_login_index');
        return $resultPage;
    }
}