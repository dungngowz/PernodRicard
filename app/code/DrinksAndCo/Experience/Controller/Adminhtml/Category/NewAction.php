<?php

namespace DrinksAndCo\Experience\Controller\Adminhtml\Category;

class NewAction extends \DrinksAndCo\Experience\Controller\Adminhtml\Experience
{
    public function execute()
    {
        $this->_forward('edit');
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('DrinksAndCo_Experience::edit_category');
    }
}
