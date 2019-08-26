<?php

namespace DrinksAndCo\History\Controller\Post;

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

    public function _initPost()
    {
        $postId = (int)$this->getRequest()->getParam('post_id', false);
        if (!$postId) {
            return false;
        }
        try {
            $post = $this->_postModel->load($postId);
        } catch (\Exception $e) {
            return false;
        }
        $this->_coreRegistry->register('current_post', $post);
        return $post;
    }

    public function execute()
    {
        if (!$this->historyHelper->getConfig('general_settings/enabled')) {
            return $this->resultForwardFactory->create()->forward('noroute');
        }
        $post = $this->_initPost();
        if ($post) {
            $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
            if ($this->historyHelper->getConfig('general_settings/template_detail')) {
                $resultPage->getConfig()->setPageLayout($this->historyHelper->getConfig('general_settings/template_detail'));
            }
            return $resultPage;
        } else {
            return $this->resultForwardFactory->create()->forward('noroute');
        }
    }
}
