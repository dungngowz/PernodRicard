<?php

namespace DrinksAndCo\Blog\Controller\Adminhtml\Post;

use DrinksAndCo\Blog\Controller\Adminhtml\Blog;

class Index extends Blog
{
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('DrinksAndCo_Blog::manage_post');
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Posts'));
        $resultPage->addBreadcrumb(__('Blog'), __('Blog'));
        $resultPage->addBreadcrumb(__('Manage Posts'), __('Manage Posts'));
        return $resultPage;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('DrinksAndCo_Blog::manage_post');
    }
}
