<?php

namespace DrinksAndCo\Blog\Controller\Adminhtml\Post;

class NewAction extends \DrinksAndCo\Blog\Controller\Adminhtml\Blog
{
    public function execute()
    {
        $this->_forward('edit');
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('DrinksAndCo_Blog::edit_post');
    }
}
