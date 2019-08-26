<?php

namespace DrinksAndCo\Experience\Controller\Adminhtml\Comment;

use DrinksAndCo\Experience\Controller\Adminhtml\Experience;

class MassStatus extends Experience
{
    public function execute()
    {
        $commentIds = $this->getRequest()->getParam('comment');
        if (!is_array($commentIds) || empty($commentIds)) {
            $this->messageManager->addError(__('Please select comment(s).'));
        } else {
            try {
                foreach ($commentIds as $id) {
                    $comment = $this->_objectManager->create('DrinksAndCo\Experience\Model\Comment')->load($id);
                    $comment->setStatus($this->getRequest()->getParam('status'))->save();
                }
                $this->messageManager->addSuccess(__('Total of %1 comment(s) were changed status.', count($commentIds)));
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $this->_redirect('experience/comment/index');
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('DrinksAndCo_Experience::save_comment');
    }
}
