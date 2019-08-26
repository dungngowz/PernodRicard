<?php
namespace DrinksAndCo\CustomizeCart\Controller\Index;

class Clearcart extends \Magento\Framework\App\Action\Action{

    protected $resultJsonFactory;

    /**
     * @var \Magento\Framework\Escaper
     */
    protected $escaper;
    
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        array $data = []
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
    }

	public function execute(){
        $resultJson = $this->resultJsonFactory->create();

		$objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
		$cartObject = $objectManager->create('Magento\Checkout\Model\Cart')->truncate(); 
        $cartObject->saveQuote();
         
        //Return message to client
        $response = ['code' => 'success-invite', 'msg' => __('You have successfully clear cart') ];
        $resultJson->setData($response); return $resultJson;
    }
    
}