<?php

namespace DrinksAndCo\Blog\Controller\Adminhtml\Comment;

use DrinksAndCo\Blog\Controller\Adminhtml\Blog;

class Grid extends Blog
{
    public function execute()
    {
        $this->_view->loadLayout(false);
        $this->_view->renderLayout();
    }
}
