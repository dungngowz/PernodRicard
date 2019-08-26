<?php

namespace DrinksAndCo\History\Controller\Adminhtml\Comment;

use Magento\Backend\App\Action;

class Unapprove extends Action
{
    public function execute()
    {
        $id = $this->getRequest()->getParam('comment_id');
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $model = $this->_objectManager->create('DrinksAndCo\History\Model\Comment');
                $model->load($id);
                $model->setStatus(2);
                $model->save();
                $this->messageManager->addSuccess(__('The comment has been unapproved.'));
                return $resultRedirect->setPath('history/comment/index');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('history/comment/edit', ['comment_id' => $id]);
            }
        }
        $this->messageManager->addError(__('We can\'t find a comment to unapprove.'));
        return $resultRedirect->setPath('history/comment/index');
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('DrinksAndCo_History::save_comment');
    }
}
