<?php

namespace DrinksAndCo\History\Controller\Adminhtml\Comment;

use DrinksAndCo\History\Controller\Adminhtml\History;

class Index extends History
{
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('DrinksAndCo_History::manage_comment');
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Comments'));
        $resultPage->addBreadcrumb(__('History'), __('History'));
        $resultPage->addBreadcrumb(__('Manage Categories'), __('Manage Comments'));
        return $resultPage;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('DrinksAndCo_History::manage_comment');
    }
}
