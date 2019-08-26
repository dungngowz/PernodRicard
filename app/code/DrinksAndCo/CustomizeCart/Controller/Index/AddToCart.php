<?php
namespace DrinksAndCo\CustomizeCart\Controller\Index;

use Magento\Quote\Model\QuoteFactory;
use Magento\Quote\Api\CartRepositoryInterface;

class AddToCart extends \Magento\Framework\App\Action\Action
{
    protected $formKey;  
    protected $cart;
    protected $product;
    protected $resultJsonFactory;
    protected $quote;
    protected $cartRep;

    /**
     * @var \Magento\Framework\Escaper
     */
    protected $escaper;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $session;
    
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Data\Form\FormKey $formKey,
        \Magento\Checkout\Model\Cart $cart,
        \Magento\Catalog\Model\Product $product,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\Escaper $escaper,
        \Magento\Customer\Model\Session $session,
        QuoteFactory $quote,
        CartRepositoryInterface $cartRep,
        array $data = []
    ) {
        $this->formKey = $formKey;
        $this->cart = $cart;
        $this->product = $product;  
        $this->resultJsonFactory = $resultJsonFactory;
        $this->escaper = $escaper;
        $this->quote = $quote;
        $this->cartRep = $cartRep;
        $this->session = $session;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();

        try {
            $qty = intval($this->getRequest()->getPost('qty'));

            $quote = $this->cartRep->getActiveForCustomer($this->session->getId());
            $cart = $this->quote->create()->loadActive($quote->getId());

            $productId = $this->getRequest()->getPost('product_id');
                
            //Load the product based on productID  
            $_product = $this->product->load($productId);
            
            $quoteItem = $cart->addProduct($_product, $qty);
            $quoteItem->setPrice($_product->getPrice());

            $allItem = $quote->getAllItems();
            foreach($allItem as $item){
                $price = intval($item->getPrice());
                if($price == 0){
                    $itemId = $item->getItemId();
                    $quote->removeItem($itemId)->save();
                }
            }

            $cart->collectTotals();
            $cart->save();
        } catch (\Exception $e) {
            $response = [ 'code' => 500,  'msg' => $this->escaper->escapeHtml($e->getMessage() ) ];
            $resultJson->setData($response); return $resultJson;
        }
    
        //Return result to client
        $response = ['code' => 200, 'msg' => 'Add to cart successfully' ];
        $resultJson->setData($response); return $resultJson;
    }
}