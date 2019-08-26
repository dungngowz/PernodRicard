<?php

namespace DrinksAndCo\History\Controller\Tag;

use Magento\Framework\Controller\ResultFactory;

class View extends \Magento\Framework\App\Action\Action
{
    protected $historyHelper;
    protected $resultForwardFactory;
    protected $_postModel;
    protected $_coreRegistry = null;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Store\Model\StoreManager $storeManager,
        \DrinksAndCo\History\Helper\Data $historyHelper,
        \DrinksAndCo\History\Model\Post $postModel,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory
    )
    {
        $this->historyHelper = $historyHelper;
        $this->_postModel = $postModel;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context);
    }

    public function _initTag()
    {
        $tag = $this->getRequest()->getParam('tag', '');
        if (!$tag || $tag == '') {
            return false;
        }
        $this->_coreRegistry->register('current_tag', $tag);
        return $tag;
    }

    public function execute()
    {
        if (!$this->historyHelper->getConfig('general_settings/enabled')) {
            return $this->resultForwardFactory->create()->forward('noroute');
        }
        $tag = $this->_initTag();
        if ($tag != null || $tag != '') {
            $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
            if ($this->historyHelper->getConfig('general_settings/template')) {
                $resultPage->getConfig()->setPageLayout($this->historyHelper->getConfig('general_settings/template'));
            }
            return $resultPage;
        } else {
            return $this->resultForwardFactory->create()->forward('noroute');
        }
    }
}
