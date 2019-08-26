<?php

namespace DrinksAndCo\Experience\Controller\Adminhtml\Comment;

use DrinksAndCo\Experience\Controller\Adminhtml\Experience;

class Index extends Experience
{
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('DrinksAndCo_Experience::manage_comment');
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Comments'));
        $resultPage->addBreadcrumb(__('Experience'), __('Experience'));
        $resultPage->addBreadcrumb(__('Manage Categories'), __('Manage Comments'));
        return $resultPage;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('DrinksAndCo_Experience::manage_comment');
    }
}
