<?php

namespace DrinksAndCo\Blog\Controller\Adminhtml\Category;

class NewAction extends \DrinksAndCo\Blog\Controller\Adminhtml\Blog
{
    public function execute()
    {
        $this->_forward('edit');
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('DrinksAndCo_Blog::edit_category');
    }
}
