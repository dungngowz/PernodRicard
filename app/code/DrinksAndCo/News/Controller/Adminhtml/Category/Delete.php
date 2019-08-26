<?php

namespace DrinksAndCo\News\Controller\Adminhtml\Category;

use Magento\Backend\App\Action;

class Delete extends Action
{
    public function execute()
    {
        $id = $this->getRequest()->getParam('category_id');
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $model = $this->_objectManager->create('DrinksAndCo\News\Model\Category');
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccess(__('The category has been deleted.'));
                return $resultRedirect->setPath('news/category/index');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('news/category/edit', ['category_id' => $id]);
            }
        }
        $this->messageManager->addError(__('We can\'t find a category to delete.'));
        return $resultRedirect->setPath('news/category/index');
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('DrinksAndCo_News::delete_category');
    }
}
