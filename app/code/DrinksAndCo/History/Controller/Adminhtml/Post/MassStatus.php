<?php

namespace DrinksAndCo\History\Controller\Adminhtml\Post;

use DrinksAndCo\History\Controller\Adminhtml\History;

class MassStatus extends History
{
    public function execute()
    {
        $postIds = $this->getRequest()->getParam('post');
        if (!is_array($postIds) || empty($postIds)) {
            $this->messageManager->addError(__('Please select post(s).'));
        } else {
            try {
                foreach ($postIds as $id) {
                    $post = $this->_objectManager->create('DrinksAndCo\History\Model\Post')->load($id);
                    $post->setStatus($this->getRequest()->getParam('status'))->save();
                }
                $this->messageManager->addSuccess(__('Total of %1 post(s) were changed status.', count($postIds)));
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $this->_redirect('history/post/index');
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('DrinksAndCo_History::save_post');
    }
}
