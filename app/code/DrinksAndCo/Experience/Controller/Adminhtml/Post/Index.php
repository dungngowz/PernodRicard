<?php

namespace DrinksAndCo\Experience\Controller\Adminhtml\Post;

use DrinksAndCo\Experience\Controller\Adminhtml\Experience;

class Index extends Experience
{
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('DrinksAndCo_Experience::manage_post');
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Posts'));
        $resultPage->addBreadcrumb(__('Experience'), __('Experience'));
        $resultPage->addBreadcrumb(__('Manage Posts'), __('Manage Posts'));
        return $resultPage;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('DrinksAndCo_Experience::manage_post');
    }
}
