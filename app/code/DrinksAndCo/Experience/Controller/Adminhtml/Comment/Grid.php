<?php

namespace DrinksAndCo\Experience\Controller\Adminhtml\Comment;

use DrinksAndCo\Experience\Controller\Adminhtml\Experience;

class Grid extends Experience
{
    public function execute()
    {
        $this->_view->loadLayout(false);
        $this->_view->renderLayout();
    }
}
