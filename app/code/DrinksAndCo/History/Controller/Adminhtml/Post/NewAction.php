<?php

namespace DrinksAndCo\History\Controller\Adminhtml\Post;

class NewAction extends \DrinksAndCo\History\Controller\Adminhtml\History
{
    public function execute()
    {
        $this->_forward('edit');
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('DrinksAndCo_History::edit_post');
    }
}
