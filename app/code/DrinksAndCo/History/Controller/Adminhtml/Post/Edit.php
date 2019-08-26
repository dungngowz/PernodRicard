<?php

namespace DrinksAndCo\History\Controller\Adminhtml\Post;

use DrinksAndCo\History\Controller\Adminhtml\History;

class Edit extends History
{
    public function execute()
    {
        $id = $this->getRequest()->getParam('post_id');
        $model = $this->_objectManager->create('DrinksAndCo\History\Model\Post');

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This post no longer exists.'));
                $this->_redirect('history/post/index');
                return;
            }
        }
        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getPageData(true);
        if (!empty($data)) {
            $model->addData($data);
        }
        $this->_coreRegistry->register('current_post', $model);
        $this->_initAction()->_addBreadcrumb(
            $id ? __('Edit Post') : __('Add New Post'),
            $id ? __('Edit Post') : __('Add New Post')
        );
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('History'));
        $this->_view->getPage()->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getTitle() : __('Add New Post'));
        $this->_view->getLayout()->getBlock('post_edit');
        $this->_view->renderLayout();
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('DrinksAndCo_History::edit_post');
    }
}
