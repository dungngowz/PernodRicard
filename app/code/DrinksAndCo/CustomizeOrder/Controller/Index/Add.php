<?php

namespace DrinksAndCo\CustomizeOrder\Controller\Index;

class Add extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;
    protected $_commonHelper;
    protected $customizeOrderHelper;
    protected $resultJsonFactory;
    protected $customerSession;
    protected $productRepository;
    protected $catalogrule;
    protected $helperEmail;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \DrinksAndCo\Base\Helper\Common $commonHelper,
        \Magento\CatalogRule\Model\Rule $catalogrule,
        \DrinksAndCo\CustomizeOrder\Helper\Data $customizeOrderHelper,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Customer\Model\Session $customerSession,
        \DrinksAndCo\Customer\Helper\Email $helperEmail
    ){
        $this->_pageFactory = $pageFactory;
        $this->_commonHelper = $commonHelper;
        $this->customizeOrderHelper = $customizeOrderHelper;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->customerSession = $customerSession;
        $this->catalogrule = $catalogrule;
        $this->productRepository = $productRepository;
        $this->helperEmail = $helperEmail;
        return parent::__construct($context);
    }

    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();

        $totalCart = intval($this->getRequest()->getPost('totalCart'));
        $allowedOrder = $this->_commonHelper->allowedOrder($totalCart);

        if(!$allowedOrder){
            $response = ['code' => 500, 'msg' => 'You have exceeded the amount to be purchased' ];
            $resultJson->setData($response); return $resultJson;
        }

        //$price = $this->_commonHelper->getPriceProductByGroupCustomer();

        $currentCustomer = $this->customerSession->getCustomer();
        $paramsPost = $this->getRequest()->getPost();

        $group = $currentCustomer->getGroupId();
        
        //Array of product which order you want to create
        $productIds = $paramsPost['product_id'];
        $qtys = $paramsPost['qty'];

        $itemsCart = [];
        if($productIds && $qtys){
            for($i = 0; $i < count($productIds); $i++){

                $productId = intval($productIds[$i]);
                
                $product = $this->productRepository->getById($productId);

                $discountedPrice = $this->catalogrule->calcProductPriceRule(
                    $product->setStoreId(1)->setCustomerGroupId($group),
                    $product->getPrice()
                );
                
                $itemsCart[] = [
                    'product_id' => $productId,
                    'name' => $product->getName(),
                    'qty' => $qtys[$i], 
                    'price' => $discountedPrice,
                    'url' => $this->_commonHelper->getUrlDetailProduct($product->getUrlKey())
                ];
            }
        }

        list($firstname, $lastname) = $this->splitName($paramsPost['fullname']);
        $lastname = $lastname ? $lastname : '-';

        $tempOrder = [
            'currency_id'  => 'VND',
            'email'        => $paramsPost['email'],
            'export_tax_invoice' => isset($paramsPost['export_tax_invoice']) ? intval($paramsPost['export_tax_invoice']) : 0,
            'shipping_address' =>[
                'firstname'    => $firstname,
                'lastname'     => $lastname,
                'street' => $paramsPost['address'],
                'city' => '-',
                'country_id' => 'VN',
                'region' => 'xxx',
                'region_id' => $paramsPost['region'],
                'postcode' => '-',
                'telephone' => $paramsPost['phone'],
                'fax' => '',
                'save_in_address_book' => 1
            ],
            'items'=> $itemsCart
        ];

        $resOrder = $this->customizeOrderHelper->createOrder($tempOrder);
        $dataOrder = $resOrder->getData();

        //Order System send push notification to Sale Manager
        $paramsEmail = [
            'email_receipt' => $this->_commonHelper->getEmailSaleManager(),
            'template' => 'addnew_order_template',
            'order' => $this->_commonHelper->getOrderById($dataOrder['entity_id'])
        ];
        $this->helperEmail->sendEmail($paramsEmail);

        $response = ['code' => 200, 'yourOrder' => $dataOrder, 'itemsCart'=>$itemsCart ];
        $resultJson->setData($response); return $resultJson;
    }

    public function splitName($name) {
        $name = trim($name);
        $last_name = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
        $first_name = trim( preg_replace('#'.$last_name.'#', '', $name ) );
        return array($first_name, $last_name);
    }
}