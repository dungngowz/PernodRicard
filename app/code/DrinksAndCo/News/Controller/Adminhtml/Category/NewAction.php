<?php

namespace DrinksAndCo\News\Controller\Adminhtml\Category;

class NewAction extends \DrinksAndCo\News\Controller\Adminhtml\News
{
    public function execute()
    {
        $this->_forward('edit');
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('DrinksAndCo_News::edit_category');
    }
}
