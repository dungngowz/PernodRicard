<?php

namespace DrinksAndCo\News\Controller\Adminhtml\Comment;

use DrinksAndCo\News\Controller\Adminhtml\News;

class Index extends News
{
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('DrinksAndCo_News::manage_comment');
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Comments'));
        $resultPage->addBreadcrumb(__('News'), __('News'));
        $resultPage->addBreadcrumb(__('Manage Categories'), __('Manage Comments'));
        return $resultPage;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('DrinksAndCo_News::manage_comment');
    }
}
