<?php

namespace DrinksAndCo\Experience\Controller\Adminhtml\Category;

use DrinksAndCo\Experience\Controller\Adminhtml\Experience;

class Index extends Experience
{
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('DrinksAndCo_Experience::manage_category');
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Categories'));
        $resultPage->addBreadcrumb(__('Experience'), __('Experience'));
        $resultPage->addBreadcrumb(__('Manage Categories'), __('Manage Categories'));
        return $resultPage;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('DrinksAndCo_Experience::manage_category');
    }
}
