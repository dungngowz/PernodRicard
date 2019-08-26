<?php

namespace DrinksAndCo\History\Controller\Adminhtml\Post;

use DrinksAndCo\History\Controller\Adminhtml\History;

class Index extends History
{
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('DrinksAndCo_History::manage_post');
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Posts'));
        $resultPage->addBreadcrumb(__('History'), __('History'));
        $resultPage->addBreadcrumb(__('Manage Posts'), __('Manage Posts'));
        return $resultPage;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('DrinksAndCo_History::manage_post');
    }
}
