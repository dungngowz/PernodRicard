<?php

namespace DrinksAndCo\History\Controller\Adminhtml\Category;

use DrinksAndCo\History\Controller\Adminhtml\History;

class Edit extends History
{
    public function execute()
    {
        $id = $this->getRequest()->getParam('category_id');
        $model = $this->_objectManager->create('DrinksAndCo\History\Model\Category');

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This category no longer exists.'));
                $this->_redirect('history/category/index');
                return;
            }
        }
        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getPageData(true);
        if (!empty($data)) {
            $model->addData($data);
        }
        $this->_coreRegistry->register('current_category', $model);
        $this->_initAction()->_addBreadcrumb(
            $id ? __('Edit Category') : __('Add New Category'),
            $id ? __('Edit Category') : __('Add New Category')
        );
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('History'));
        $this->_view->getPage()->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getTitle() : __('Add New Category'));
        $this->_view->getLayout()->getBlock('category_edit');
        $this->_view->renderLayout();
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('DrinksAndCo_History::edit_category');
    }
}
