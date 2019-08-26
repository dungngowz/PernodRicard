<?php

namespace DrinksAndCo\History\Controller\Adminhtml\Comment;

use Magento\Backend\App\Action;

class Delete extends Action
{
    public function execute()
    {
        $id = $this->getRequest()->getParam('comment_id');
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $model = $this->_objectManager->create('DrinksAndCo\History\Model\Comment');
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccess(__('The comment has been deleted.'));
                return $resultRedirect->setPath('history/comment/index');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('history/comment/edit', ['comment_id' => $id]);
            }
        }
        $this->messageManager->addError(__('We can\'t find a comment to delete.'));
        return $resultRedirect->setPath('history/comment/index');
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('DrinksAndCo_History::delete_comment');
    }
}
