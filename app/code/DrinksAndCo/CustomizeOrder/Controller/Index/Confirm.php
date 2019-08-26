<?php

namespace DrinksAndCo\CustomizeOrder\Controller\Index;

class Confirm extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;
    protected $_commonHelper;
    protected $customizeOrderHelper;
    protected $resultJsonFactory;
    protected $customerSession;
    protected $productRepository;
    protected $catalogrule;
    protected $helperEmail;
    protected $_request;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \DrinksAndCo\Base\Helper\Common $commonHelper,
        \Magento\CatalogRule\Model\Rule $catalogrule,
        \DrinksAndCo\CustomizeOrder\Helper\Data $customizeOrderHelper,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Customer\Model\Session $customerSession,
        \DrinksAndCo\Customer\Helper\Email $helperEmail,
        \Magento\Framework\App\Request\Http $request
    ){
        $this->_pageFactory = $pageFactory;
        $this->_commonHelper = $commonHelper;
        $this->customizeOrderHelper = $customizeOrderHelper;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->customerSession = $customerSession;
        $this->catalogrule = $catalogrule;
        $this->productRepository = $productRepository;
        $this->helperEmail = $helperEmail;
        $this->_request = $request;
        return parent::__construct($context);
    }

    public function execute(){
        $params = $this->_request->getParams();

        try{
            $currentCustomer = $this->customerSession->getCustomer();
            $detailOrder = $this->_commonHelper->getOrderById($params['orderid']);

            if($detailOrder->getCustomerId() != $currentCustomer->getId()){
                echo "You are not the owner of this order";exit();
            }

            //Order System send push notification to Sale Manager
            $paramsEmail = [
                'email_receipt' => $this->_commonHelper->getEmailSaleManager(),
                'template' => 'customer_confirm_order_template',
                'order' => $detailOrder
            ];
            $this->helperEmail->sendEmail($paramsEmail);

        } catch (\Exception $e) {
            echo $e->getMessage();exit();
        }

        $resultPage = $this->_pageFactory->create();
        $resultPage->addHandle('customer_confirm_order');
        return $resultPage;
    }
}