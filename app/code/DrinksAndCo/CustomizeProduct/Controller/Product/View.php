<?php
namespace DrinksAndCo\CustomizeProduct\Controller\Product;

class View extends \Magento\Framework\App\Action\Action{
	protected $_pageFactory;
	protected $_request;
	protected $_commonHelper;

	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $pageFactory,
		\Magento\Framework\App\RequestInterface $request,
		\DrinksAndCo\Base\Helper\Common $commonHelper
	){
		$this->_pageFactory = $pageFactory;
		$this->_request = $request;
		$this->_commonHelper = $commonHelper;
		return parent::__construct($context);
	}

	public function execute(){
        
		$id = $this->_request->getParam('id');

		
		$detailProduct = $this->_commonHelper->getDetailProductById($id);

		$categoryIds = $detailProduct->getCategoryIds();

		if(!$detailProduct || empty($categoryIds)){
            return header('Location: /');
		}

		$template = 'catalog_product_detail';
		if($this->_commonHelper->isRootCategoryExp($categoryIds[0])){
			$template = 'catalog_experience_detail';
		}

		$cutomTitle = $detailProduct->getName();
		
		$resultPage = $this->_pageFactory->create();
		$resultPage->addHandle($template);
		$this->_commonHelper->setTitlePage($resultPage->getConfig(), $cutomTitle);
		
		return $resultPage;
	}
}
