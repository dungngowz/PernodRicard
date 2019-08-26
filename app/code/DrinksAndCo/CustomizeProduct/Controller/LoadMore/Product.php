<?php
namespace DrinksAndCo\CustomizeProduct\Controller\LoadMore;
class Product extends \Magento\Framework\App\Action\Action
{
	protected $_pageFactory;
	protected $_request;
	protected $_dataHelper;
	protected $_commonHelper;
	protected $resultJsonFactory;
	/**
     * @var \Magento\Framework\App\ViewInterface
     */
    protected $_view;
	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $pageFactory,
		\Magento\Framework\App\RequestInterface $request,
		\DrinksAndCo\CustomizeProduct\Helper\Data $dataHelper,
		\Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
		\DrinksAndCo\Base\Helper\Common $commonHelper
	){
		$this->_pageFactory = $pageFactory;
		$this->_request = $request;
		$this->_dataHelper = $dataHelper;
		$this->_commonHelper = $commonHelper;
		$this->resultJsonFactory = $resultJsonFactory;
		$this->_view = $context->getView();
		return parent::__construct($context);
	}
	public function execute()
	{
		$html = $this->_view->getLayout()
                 ->createBlock("DrinksAndCo\CustomizeProduct\Block\Product")
                 ->setTemplate("DrinksAndCo_CustomizeProduct::product/load-more-product.phtml")
				 ->toHtml();
				 
		$pageCurrent = trim($this->_request->getParam('p'), '/');
        $resultJson = $this->resultJsonFactory->create();
        $response = ['code' => 200, 'pageCurrent' => $pageCurrent, 'html' => $html ];
        $resultJson->setData($response); 
        return $resultJson;
	}
}
