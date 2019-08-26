<?php
namespace DrinksAndCo\CustomizeCart\Controller\Index;

use Magento\Quote\Model\QuoteFactory;
use Magento\Quote\Api\CartRepositoryInterface;

class UpdateToCart extends \Magento\Framework\App\Action\Action
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
            $id = intval($this->getRequest()->getPost('id'));
            $qty = intval($this->getRequest()->getPost('qty'));

            $quote = $this->cartRep->getActiveForCustomer($this->session->getId());
            $cart = $this->quote->create()->loadActive($quote->getId());

            if($qty == 0){
                $cart->removeItem($id); 
            }else{
                $cart->updateItem($id, ['qty'=>$qty]);
            }
            
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

            $dataCart = $cart->getData();

            if($dataCart['items_qty'] < 1){
                $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
                $cartObject = $objectManager->create('Magento\Checkout\Model\Cart')->truncate(); 
                $cartObject->saveQuote();
            }

            //Return result to client
            $htmlCart = $this->_view->getLayout()
                ->createBlock("DrinksAndCo\CustomizeCart\Block\Confirm")
                ->setTemplate("DrinksAndCo_CustomizeCart::detail-cart.phtml")
                ->toHtml();

        } catch (\Exception $e) {
            $response = [ 'code' => 500, 'msg' => $this->escaper->escapeHtml($e->getMessage() ) ];
            $resultJson->setData($response); return $resultJson;
        }

        $response = ['code' => 200, 'htmlCart' => $htmlCart, 'total' => $cart->getData(),  'msg' => 'You have successfully updated your shopping cart' ];
        $resultJson->setData($response); return $resultJson;
    }
}