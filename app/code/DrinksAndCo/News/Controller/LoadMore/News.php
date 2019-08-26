<?php
namespace DrinksAndCo\News\Controller\LoadMore;

class News extends \Magento\Framework\App\Action\Action
{
	protected $_pageFactory;
	protected $_request;
	
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
		\Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
		\DrinksAndCo\Base\Helper\Common $commonHelper
	){
		$this->_pageFactory = $pageFactory;
		$this->_request = $request;
		$this->_commonHelper = $commonHelper;
		$this->resultJsonFactory = $resultJsonFactory;
		$this->_view = $context->getView();
		return parent::__construct($context);
	}

	public function execute()
	{
		$html = $this->_view->getLayout()
                 ->createBlock("DrinksAndCo\News\Block\Posts")
                 ->setTemplate("DrinksAndCo_News::load-more-news.phtml")
				 ->toHtml();
				 
		$pageCurrent = trim($this->_request->getParam('p'), '/');

        $resultJson = $this->resultJsonFactory->create();
        $response = ['code' => 200, 'pageCurrent' => $pageCurrent, 'html' => $html ];
        $resultJson->setData($response); 
        return $resultJson;
	}
}