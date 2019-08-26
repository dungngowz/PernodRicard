<?php

namespace DrinksAndCo\News\Controller\Adminhtml\Post;

use DrinksAndCo\News\Controller\Adminhtml\News;

class Index extends News
{
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('DrinksAndCo_News::manage_post');
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Posts'));
        $resultPage->addBreadcrumb(__('News'), __('News'));
        $resultPage->addBreadcrumb(__('Manage Posts'), __('Manage Posts'));
        return $resultPage;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('DrinksAndCo_News::manage_post');
    }
}
