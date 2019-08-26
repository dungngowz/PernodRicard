<?php

namespace DrinksAndCo\Experience\Controller\Adminhtml\Post;

class NewAction extends \DrinksAndCo\Experience\Controller\Adminhtml\Experience
{
    public function execute()
    {
        $this->_forward('edit');
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('DrinksAndCo_Experience::edit_post');
    }
}
