<?php

namespace DrinksAndCo\Blog\Controller\Adminhtml\Category;

use DrinksAndCo\Blog\Controller\Adminhtml\Blog;

class Index extends Blog
{
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('DrinksAndCo_Blog::manage_category');
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Categories'));
        $resultPage->addBreadcrumb(__('Blog'), __('Blog'));
        $resultPage->addBreadcrumb(__('Manage Categories'), __('Manage Categories'));
        return $resultPage;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('DrinksAndCo_Blog::manage_category');
    }
}
