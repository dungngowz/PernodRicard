<?php

namespace DrinksAndCo\News\Controller\Adminhtml\Post;

use DrinksAndCo\News\Controller\Adminhtml\News;

class Grid extends News
{
    public function execute()
    {
        $this->_view->loadLayout(false);
        $this->_view->renderLayout();
    }
}
