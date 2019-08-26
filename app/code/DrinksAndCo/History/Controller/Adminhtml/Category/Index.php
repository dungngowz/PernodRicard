<?php

namespace DrinksAndCo\History\Controller\Adminhtml\Category;

use DrinksAndCo\History\Controller\Adminhtml\History;

class Index extends History
{
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('DrinksAndCo_History::manage_category');
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Categories'));
        $resultPage->addBreadcrumb(__('History'), __('History'));
        $resultPage->addBreadcrumb(__('Manage Categories'), __('Manage Categories'));
        return $resultPage;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('DrinksAndCo_History::manage_category');
    }
}
