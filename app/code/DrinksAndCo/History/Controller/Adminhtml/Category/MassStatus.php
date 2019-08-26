<?php

namespace DrinksAndCo\History\Controller\Adminhtml\Category;

use DrinksAndCo\History\Controller\Adminhtml\History;

class MassStatus extends History
{
    public function execute()
    {
        $categoryIds = $this->getRequest()->getParam('category');
        if (!is_array($categoryIds) || empty($categoryIds)) {
            $this->messageManager->addError(__('Please select category(s).'));
        } else {
            try {
                foreach ($categoryIds as $id) {
                    $category = $this->_objectManager->create('DrinksAndCo\History\Model\Category')->load($id);
                    $category->setStatus($this->getRequest()->getParam('status'))->save();
                }
                $this->messageManager->addSuccess(__('Total of %1 category(s) were changed status.', count($categoryIds)));
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $this->_redirect('history/category/index');
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('DrinksAndCo_History::save_category');
    }
}
