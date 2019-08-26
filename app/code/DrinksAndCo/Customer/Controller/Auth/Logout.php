<?php

namespace DrinksAndCo\Customer\Controller\Auth;

class Logout extends \Magento\Framework\App\Action\Action{
    protected $redirect;
    protected $customerSession;
    protected $pageFactory;
    protected $commonHelper;
    protected $_customCooike;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \DrinksAndCo\Base\Helper\Common $commonHelper,
        \DrinksAndCo\Customer\Helper\Data $customCooike
    ){
        $this->redirect = $context->getRedirect();
        $this->customerSession = $customerSession;
        $this->commonHelper = $commonHelper;
        $this->_customCooike = $customCooike;
        $this->pageFactory = $pageFactory;
        return parent::__construct($context);
    }

    public function execute(){
        $customerId = $this->customerSession->getId();
        if($customerId) {
            $this->_customCooike->delete('rememberme');
            $this->customerSession->logout()
                 ->setBeforeAuthUrl($this->redirect->getRefererUrl())
                 ->setLastCustomerId($customerId);
            return $this->commonHelper->redirectToHomePage();
        } else {
            echo "Customer is not login";
        }
    }
}