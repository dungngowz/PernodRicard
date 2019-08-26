<?php
namespace DrinksAndCo\CustomizeProduct\Block;

use Magento\Framework\View\Element\Template;

class Experience extends Template
{
    public $_request;
    protected $_productFactory;
    protected $_categoryFactory;
    protected $_url;
    protected $_productHelper;
    protected $_imageBuilder;
    protected $eavConfig;
    protected $_productCollectionFactory;
    public $_commonHelper;
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
        \Magento\Framework\Url $url,
        \DrinksAndCo\Base\Helper\Common $commonHelper,
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
        $this->_commonHelper = $commonHelper;
        $this->_url = $url;
        $this->imageHelper = $imageHelper;
    }

    public function getRootCategoryExperience(){
        return $this->_productHelper->getCategoryCollection('experience');
    }

    public function getChildCategoryExperience(){
        return $this->_commonHelper->getChildCategoryExperience();
    }

    public function getProductsByCategory($returnTotal = false, $pageSize = 12){
        $currentPage = $this->_request->getParam('p') ? $this->_request->getParam('p') : 1;

        $id = $this->_request->getParam('id');
        $category = $this->_commonHelper->loadCategoryById($id);
        $collection = $category->getProductCollection()->addAttributeToSelect('*');

        $collection->setPageSize($pageSize)
            ->setCurPage($currentPage)
            ->setOrder('created_at', 'desc');

        if($returnTotal){
            return $collection->count();
        }

        return $collection;
    }

    public function getOptionAttribute($attributeCode){
        $attribute = $this->eavConfig->getAttribute('catalog_product', $attributeCode);
        $options = $attribute->getSource()->getAllOptions();
        return $options;
    }

    public function getDetailProduct(){
        $id = $this->_request->getParam('id');
		return $this->_commonHelper->getDetailProductById($id);
    }

    public function getUrlDetailProduct($sku){
        return $this->getUrl(__('detail-experience') . '/' . $sku);
    }

    public function getRecentProduct(){
        return $this->_productHelper->getRecentProduct();
    }

    public function getRelatedExperience($id){
        return $this->_commonHelper->getRelatedProduct($id);
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

    public function getLoadMoreExpUrl(){
        return trim($this->getUrl('catalog/loadmore/experience?' . $this->getParamsSearch()), '/');
    }
    
    public function getInfomationConfirmUrl(){
        return trim($this->getUrl(__('information-confirm'). '.html'));
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

    public function getCatProductUrl($path){
        return $this->getUrl(__('url-experiences') . '/' . $path);
    }

    public function checkActiveCatExp($idCat){
        $id = $this->_request->getParam('id');
        return ($id == $idCat) ? true : false;
    }

    public function getBreadcrumbsExpList(){
        $categoryId = $this->_request->getParam('id');

        $rootCategoryExp = $this->_commonHelper->getDetailRootCategoryExp();
        $breadcrumbs = [
            [
                'text' => __('Experience'),
                'url' => $rootCategoryExp->getUrl()
            ]
        ];

        $categoryCurrent = $this->_commonHelper->loadCategoryById($categoryId);
        if(!$this->_commonHelper->isRootCategoryExp($categoryId)){
            $breadcrumbs[] = [
                'text' => $categoryCurrent->getName(),
                'url' => $categoryCurrent->getUrl()
            ];
        }

        return $breadcrumbs;
    }

    public function getBreadcrumbsExpDetail(){
        $id = $this->_request->getParam('id');
        $detail = $this->_commonHelper->getDetailProductById($id);
        $categoryIds = $detail->getCategoryIds();
        
        $breadcrumbs = [];

        if($categoryIds){
            foreach($categoryIds as $id){
                $item = $this->_commonHelper->loadCategoryById($id);
                $breadcrumbs[] = [
                    'text' => $item->getName(),
                    'url' => $item->getUrl()
                ];
            }
        }
        
        return $breadcrumbs;
    }

    public function getResizeImage($id, $width = 270){
        //return $imageName;
        return $this->_commonHelper->resizeImage(1, 'experiences_thumbnail_image', $width);
    }
}
