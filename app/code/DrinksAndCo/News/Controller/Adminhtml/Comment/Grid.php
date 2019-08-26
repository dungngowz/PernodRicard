<?php

namespace DrinksAndCo\News\Controller\Adminhtml\Comment;

use DrinksAndCo\News\Controller\Adminhtml\News;

class Grid extends News
{
    public function execute()
    {
        $this->_view->loadLayout(false);
        $this->_view->renderLayout();
    }
}
