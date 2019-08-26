<?php
namespace DrinksAndCo\CustomizeProduct\Block;

use Magento\Framework\View\Element\Template;

class Product extends Template
{
    public $_request;
    protected $_productFactory;
    protected $_categoryFactory;
    protected $_url;
    protected $_productHelper;
    protected $_imageBuilder;
    protected $eavConfig;
    protected $_productCollectionFactory;
    protected $_dataHelper;
    protected $_commonHelper;
    protected $imageHelper;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \DrinksAndCo\CustomizeProduct\Helper\Data $productHelper,
        \Magento\Catalog\Block\Product\ImageBuilder $imageBuilder,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory, 
        \Magento\Eav\Model\Config $eavConfig,
        \DrinksAndCo\CustomizeProduct\Helper\Data $dataHelper,
        \DrinksAndCo\Base\Helper\Common $commonHelper,
        \Magento\Framework\Url $url,
        \Magento\Catalog\Helper\Image $imageHelper
    ){
        parent::__construct($context);
        $this->_request = $request;
        $this->_categoryFactory = $categoryFactory;
        $this->_productFactory = $productFactory;
        $this->_productHelper = $productHelper;
        $this->_imageBuilder = $imageBuilder;
        $this->eavConfig = $eavConfig;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_dataHelper = $dataHelper;
        $this->_url = $url;
        $this->_commonHelper = $commonHelper;
        $this->imageHelper = $imageHelper;
    }

    public function getCurrentCategory(){
        return $this->_request->getParam('path');
    }

    public function getProductsByCategory($returnTotal = false, $pageSize = 12){
        $currentPage = $this->_request->getParam('p') ? $this->_request->getParam('p') : 1;

        $id = $this->_request->getParam('id');

        $category = $this->_commonHelper->loadCategoryById($id);

        $collection = $category->getProductCollection()->addAttributeToSelect('*');

        //Get product by customer group
        $currentCustomerGroup = $this->_commonHelper->getCustomerGroupCatalog();
        $collection->addAttributeToFilter('customer_groups', ['finset' => $currentCustomerGroup]);

        $keyword = $this->_request->getParam('keyword');
        if($keyword){
            //$collection->addAttributeToFilter('name', array('like' => '%'.$keyword.'%'));

            $collection->addFieldToFilter(array(
                array(
                    'attribute' => 'name',
                    'like' => '%'.$keyword.'%'),
                array(
                    'attribute' => 'description',
                    'like' => '%'.$keyword.'%'),
                array(
                    'attribute' => 'short_description',
                    'like' => '%'.$keyword.'%')
            ));
        }

        $size = $this->_request->getParam('sizes');
        if($size){
            $size = explode(',', $size);
            $collection->addAttributeToFilter('size_litre', array('in' => $size));
        }

        $alcoholLevel = $this->_request->getParam('alcohol-level');
        if($alcoholLevel){
            $alcoholLevel = explode(',', $alcoholLevel);
            $collection->addAttributeToFilter('alcohol_level', array('in' => $alcoholLevel));
        }

        if($returnTotal){
            return $collection->count();
        }

        $collection->setPageSize($pageSize)
            ->setCurPage($currentPage)
            ->setOrder('created_at', 'desc');

        return $collection;
    }

    public function convertVolumes($item) {
        $result ='' ;
        if($item < 1)
            $result = $item*1000 . 'CL' ;
        else
        $result = $item . 'L' ;
        return $result ;

    }

    public function getOptionAttribute($attributeCode){
        $attribute = $this->eavConfig->getAttribute('catalog_product', $attributeCode);
        $options = $attribute->getSource()->getAllOptions();
        return $options;
    }

    public function getDetailProduct(){
        $id = $this->_request->getParam('id');
        $product = $this->_commonHelper->getDetailProductById($id);

        if($product->getCustomAttribute('customer_groups')){
            $customerGroups = $product->getCustomAttribute('customer_groups')->getValue();
            if($customerGroups){
                $customerGroups = explode(',', $customerGroups);
                $currentCustomerGroup = $this->_commonHelper->getCustomerGroupCatalog();

                if(!in_array($currentCustomerGroup, $customerGroups)){
                    header('Location: /');
                }
            }
        }
        

        return $product;
    }

    public function getUrlCategory(){
        $id = $this->_request->getParam('id');
        $category = $this->_commonHelper->loadCategoryById($id);
        return $category ? $category->getUrl() : '';
    }

    public function getUrlSearchProduct($key, $value){
        list($currentUrl) = explode('?',$this->_url->getCurrentUrl());

        if($key == 'alcohol-level'){
            $queryString = 'size=' . $this->_request->getParam('size') . '&alcohol-level=' . $value;
        }else{
            $queryString = 'alcohol-level=' . $this->_request->getParam('alcohol-level') . '&size=' . $value;
        }

        return $currentUrl . '?' . $queryString;
    }

    public function getUrlDetailProduct($sku){
        return $this->getUrl(__('detail-product') . '/' . $sku);
    }

    public function getRecentProduct(){
        return $this->_commonHelper->getRecentProduct('products');
    }

    public function getRelatedProduct($id){
        return $this->_commonHelper->getRelatedProduct($id, 'products');
    }

    public function getImage($product){
        return $this->imageHelper->init($product, 'product_base_image')->getUrl();
    }

    public function getPostActionAddToCartUrl(){
        return $this->getUrl('customizecart/index/addtocart');
    }

    public function getLoadMoreProductUrl(){
        return trim($this->getUrl('catalog/loadmore/product?' . $this->getParamsSearch()), '/');
    }

    public function getInfomationConfirmUrl(){
        return trim($this->getUrl(__('information-confirm'). '.html')) ;
    }

    public function getParamsSearch(){
        $params = $this->_request->getParams();

        $queryString = [];
        if($params){
            foreach($params as $key => $item){
                if(!in_array($key, ['p', 'page'])){
                    $queryString[] = $key . '=' . $item;
                }
            }
        }

        return implode('&', $queryString);
    }

    public function getCategoryRootProduct(){
        return $this->_categoryFactory->create()->loadByAttribute('url_key', 'products');
    }

    public function getUrlFilter($slug){
        $url = __('product') . '/' . $slug;

        $params = [];
        $size = $this->_request->getParam('size');
        if($size){
            $params[] = 'size=' . $size;
        }

        $alcoholLevel = $this->_request->getParam('alcohol-level');
        if($alcoholLevel){
            $params[] = 'alcohol-level=' . $alcoholLevel;
        }

        if($params){
            $url .= '?' . implode('&', $params);
        }

        return $this->getUrl($url);
    }
}
