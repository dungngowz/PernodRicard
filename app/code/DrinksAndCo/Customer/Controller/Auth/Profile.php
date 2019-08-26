<?php

namespace DrinksAndCo\Customer\Controller\Auth;

class Profile extends \Magento\Framework\App\Action\Action{
    protected $redirect;
    protected $customerSession;
    protected $_pageFactory;
    protected $commonHelper;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magento\Customer\Model\Session $customerSession,
        \DrinksAndCo\Base\Helper\Common $commonHelper
    ){
        $this->_pageFactory = $pageFactory;
        $this->customerSession = $customerSession;
        $this->commonHelper = $commonHelper;
        return parent::__construct($context);
    }

    public function execute(){
        $customerId = $this->customerSession->getId();

        if(!$customerId) {
            return $this->commonHelper->redirectToHomePage();
        }

        $resultPage = $this->_pageFactory->create();
        $resultPage->addHandle('customer_auth_profile');
        return $resultPage;
    }
}