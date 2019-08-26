<?php

namespace DrinksAndCo\News\Controller\Adminhtml\Comment;

use DrinksAndCo\News\Controller\Adminhtml\News;

class MassStatus extends News
{
    public function execute()
    {
        $commentIds = $this->getRequest()->getParam('comment');
        if (!is_array($commentIds) || empty($commentIds)) {
            $this->messageManager->addError(__('Please select comment(s).'));
        } else {
            try {
                foreach ($commentIds as $id) {
                    $comment = $this->_objectManager->create('DrinksAndCo\News\Model\Comment')->load($id);
                    $comment->setStatus($this->getRequest()->getParam('status'))->save();
                }
                $this->messageManager->addSuccess(__('Total of %1 comment(s) were changed status.', count($commentIds)));
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $this->_redirect('news/comment/index');
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('DrinksAndCo_News::save_comment');
    }
}
