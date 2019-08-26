<?php

namespace DrinksAndCo\History\Controller\Adminhtml\Comment;

use DrinksAndCo\History\Controller\Adminhtml\History;

class MassStatus extends History
{
    public function execute()
    {
        $commentIds = $this->getRequest()->getParam('comment');
        if (!is_array($commentIds) || empty($commentIds)) {
            $this->messageManager->addError(__('Please select comment(s).'));
        } else {
            try {
                foreach ($commentIds as $id) {
                    $comment = $this->_objectManager->create('DrinksAndCo\History\Model\Comment')->load($id);
                    $comment->setStatus($this->getRequest()->getParam('status'))->save();
                }
                $this->messageManager->addSuccess(__('Total of %1 comment(s) were changed status.', count($commentIds)));
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $this->_redirect('history/comment/index');
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('DrinksAndCo_History::save_comment');
    }
}
