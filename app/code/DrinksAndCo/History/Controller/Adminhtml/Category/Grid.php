<?php

namespace DrinksAndCo\History\Controller\Adminhtml\Category;

use DrinksAndCo\History\Controller\Adminhtml\History;

class Grid extends History
{
    public function execute()
    {
        $this->_view->loadLayout(false);
        $this->_view->renderLayout();
    }
}
