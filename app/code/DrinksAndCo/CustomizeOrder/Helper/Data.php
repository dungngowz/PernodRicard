<?php
namespace DrinksAndCo\CustomizeOrder\Helper;

class Data
{
    protected $orderRepository;
    protected $commonHelper;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Quote\Model\QuoteManagement $quoteManagement,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Sales\Model\Service\OrderService $orderService,
        \Magento\Quote\Api\CartRepositoryInterface $cartRepositoryInterface,
        \Magento\Quote\Api\CartManagementInterface $cartManagementInterface,
        \Magento\Quote\Model\Quote\Address\Rate $shippingRate,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \DrinksAndCo\Base\Helper\Common $commonHelper
    ) {
        $this->_storeManager = $storeManager;
        $this->_productFactory = $productFactory;
        $this->quoteManagement = $quoteManagement;
        $this->customerFactory = $customerFactory;
        $this->customerRepository = $customerRepository;
        $this->orderService = $orderService;
        $this->cartRepositoryInterface = $cartRepositoryInterface;
        $this->cartManagementInterface = $cartManagementInterface;
        $this->shippingRate = $shippingRate;
        $this->orderRepository = $orderRepository;
        $this->commonHelper = $commonHelper;
    }


    public function createOrder($orderData) {

        //init the store id and website id @todo pass from array
        $store = $this->_storeManager->getStore();
        $websiteId = $this->_storeManager->getStore()->getWebsiteId();

        //init the customer
        $customer=$this->customerFactory->create();
        $customer->setWebsiteId($websiteId);
        $customer->loadByEmail($orderData['email']);// load customet by email address

        //check the customer
        if(!$customer->getEntityId()){

            //If not available then create this customer
            $customer->setWebsiteId($websiteId)
                ->setStore($store)
                ->setFirstname($orderData['shipping_address']['firstname'])
                ->setLastname($orderData['shipping_address']['lastname'])
                ->setEmail($orderData['email'])
                ->setPassword($orderData['email']);

            $customer->save();
        }

        //init the quote
        $cart_id = $this->cartManagementInterface->createEmptyCart();
        $cart = $this->cartRepositoryInterface->get($cart_id);

        $cart->setStore($store);

        // if you have already had the buyer id, you can load customer directly
        $customer= $this->customerRepository->getById($customer->getEntityId());
        $cart->setCurrency();
        $cart->assignCustomer($customer); //Assign quote to customer

        //add items in quote
        foreach($orderData['items'] as $item){
            $product = $this->_productFactory->create()->load($item['product_id']);
            $cart->addProduct(
                $product,
                intval($item['qty'])
            );
        }

        //Set Address to quote @todo add section in order data for seperate billing and handle it
        $cart->getBillingAddress()->addData($orderData['shipping_address']);
        $cart->getShippingAddress()->addData($orderData['shipping_address']);

        // Collect Rates, Set Shipping & Payment Method
        $this->shippingRate
            ->setCode('freeshipping_freeshipping')
            ->getPrice(1);

        $shippingAddress = $cart->getShippingAddress();

        //@todo set in order data
        $shippingAddress->setCollectShippingRates(true)
            ->collectShippingRates()
            ->setShippingMethod('flatrate_flatrate'); //shipping method
        //$cart->getShippingAddress()->addShippingRate($this->rate);

        $cart->setPaymentMethod('checkmo'); //payment method

        //@todo insert a variable to affect the invetory
        $cart->setInventoryProcessed(false);

        // Set sales order payment
        $cart->getPayment()->importData(['method' => 'checkmo']);

        // Collect total and save
        $cart->collectTotals();

        // Submit the quote and create the order
        $cart->save();
        $cart = $this->cartRepositoryInterface->get($cart->getId());
        $order_id = $this->cartManagementInterface->placeOrder($cart->getId());

        //Clear cart
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
		$cartObject = $objectManager->create('Magento\Checkout\Model\Cart')->truncate(); 
        $cartObject->saveQuote();

        //Set delivery_at
        $detailOrder = $this->commonHelper->getOrderById($order_id);
        if($detailOrder){
            $detailOrder->setDeliveryAt($this->commonHelper->getDeliveryDate());
            $detailOrder->setExportTaxInvoice($orderData['export_tax_invoice']);
            $detailOrder->save();
        }

        return $this->orderRepository->get($order_id);
    }
}