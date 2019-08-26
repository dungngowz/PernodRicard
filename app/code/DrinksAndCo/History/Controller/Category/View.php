<?php

namespace DrinksAndCo\History\Controller\Category;

use Magento\Framework\Controller\ResultFactory;

class View extends \Magento\Framework\App\Action\Action
{
    protected $historyHelper;
    protected $resultForwardFactory;
    protected $_categoryModel;
    protected $_coreRegistry = null;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Store\Model\StoreManager $storeManager,
        \DrinksAndCo\History\Helper\Data $historyHelper,
        \DrinksAndCo\History\Model\Category $categoryModel,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory
    )
    {
        $this->historyHelper = $historyHelper;
        $this->_categoryModel = $categoryModel;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context);
    }

    public function _initCategory()
    {
        $categoryId = (int)$this->getRequest()->getParam('category_id', false);
        if (!$categoryId) {
            return false;
        }
        try {
            $category = $this->_categoryModel->load($categoryId);
        } catch (\Exception $e) {
            return false;
        }
        $this->_coreRegistry->register('current_category', $category);
        return $category;
    }

    public function execute()
    {
        if (!$this->historyHelper->getConfig('general_settings/enabled')) {
            return $this->resultForwardFactory->create()->forward('noroute');
        }
        $category = $this->_initCategory();
        if ($category) {
            $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
            return $resultPage;
        } else {
            return $this->resultForwardFactory->create()->forward('noroute');
        }
    }
}
