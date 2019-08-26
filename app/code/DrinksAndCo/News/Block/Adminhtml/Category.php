<?php

namespace DrinksAndCo\News\Block\Adminhtml;

class Category extends \Magento\Backend\Block\Widget\Grid\Container
{
    protected function _construct()
    {
        $this->_controller = 'adminhtml_category';
        $this->_blockGroup = 'DrinksAndCo_News';
        $this->_headerText = __('Manage Categories');
        $this->_addButtonLabel = __('Add New Category');
        parent::_construct();
    }
}
