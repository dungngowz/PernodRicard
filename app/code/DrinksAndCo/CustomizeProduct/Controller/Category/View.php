<?php
namespace DrinksAndCo\CustomizeProduct\Controller\Category;

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
		
		$category = $this->_commonHelper->loadCategoryById($id);

		$template = 'catalog_category_product_view';
		$rootCat = $this->_commonHelper->getRootCategory($category);
		if($this->_commonHelper->isRootCategoryExp($rootCat)){
			$template = 'catalog_category_experience_view';
		}

		$cutomTitle = $category->getName();
		
		$resultPage = $this->_pageFactory->create();
		$resultPage->addHandle($template);
		$this->_commonHelper->setTitlePage($resultPage->getConfig(), $cutomTitle);
		
		return $resultPage;
	}
}
