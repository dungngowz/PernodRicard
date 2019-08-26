<?php

namespace DrinksAndCo\Experience\Controller\Adminhtml\Post;

use DrinksAndCo\Experience\Controller\Adminhtml\Experience;

class Edit extends Experience
{
    public function execute()
    {
        $id = $this->getRequest()->getParam('post_id');
        $model = $this->_objectManager->create('DrinksAndCo\Experience\Model\Post');

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This post no longer exists.'));
                $this->_redirect('experience/post/index');
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
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Experience'));
        $this->_view->getPage()->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getTitle() : __('Add New Post'));
        $this->_view->getLayout()->getBlock('post_edit');
        $this->_view->renderLayout();
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('DrinksAndCo_Experience::edit_post');
    }
}
