<?php

namespace DrinksAndCo\Blog\Block\Adminhtml;

class Post extends \Magento\Backend\Block\Widget\Grid\Container
{
    protected function _construct()
    {
        $this->_controller = 'adminhtml_post';
        $this->_blockGroup = 'DrinksAndCo_Blog';
        $this->_headerText = __('Manage Posts');
        $this->_addButtonLabel = __('Add New Post');
        parent::_construct();
    }
}
