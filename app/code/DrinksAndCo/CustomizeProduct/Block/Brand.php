<?php
namespace DrinksAndCo\CustomizeProduct\Block;

use Magento\Framework\View\Element\Template;

class Brand extends Template
{
    protected $_dataHelper;
    protected $_commonHelper;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \DrinksAndCo\CustomizeProduct\Helper\Data $dataHelper,
        \DrinksAndCo\Base\Helper\Common $commonHelper
    ){
        $this->_dataHelper = $dataHelper;
        $this->_commonHelper = $commonHelper;
        parent::__construct($context);
    }

    public function getTopBrands(){
        return $this->_dataHelper->getCategoryCollection('level1');
    }

    public function getCategoryBrands(){
        $cateParent = $this->_request->getParam('id');
        return $this->_commonHelper->getCategoryBrands($cateParent);
    }

    public function getProductUrl($path){
        return $this->getUrl(__('product') . '/' . $path);
    }

    public function getThumbBrandActive(){
        $id = $this->_request->getParam('id');
        $category = $this->_commonHelper->loadCategoryById($id);

        if(empty($category->getImageUrl()) || $this->_commonHelper->isRootCategoryProduct($category->getId())){
            return '';
        }

        return '<img src="' . $category->getImageUrl() . '" alt="' . $category->getName() . '" title="' . $category->getName() . '"/>';
    }
    
}
