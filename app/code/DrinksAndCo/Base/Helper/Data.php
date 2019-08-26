<?php
namespace DrinksAndCo\Base\Helper;
use Magento\Framework\App\Helper\Context;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $_storeManager;

    protected $scopeConfig;

    protected $urlBuilder;

    protected $_categoryCollectionFactory;

    protected $_productCollectionFactory;

    protected $_catalogProductVisibility;

    protected $_resource;

    public function __construct(
        Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magento\Framework\App\ResourceConnection $resource
    )
    {
        $this->_storeManager = $storeManager;
        $this->scopeConfig = $context->getScopeConfig();
        $this->_categoryCollectionFactory = $categoryCollectionFactory;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_catalogProductVisibility = $catalogProductVisibility;
        $this->_resource                 = $resource;
        parent::__construct($context);
        $this->urlBuilder = $context->getUrlBuilder();

    }
    public function getStoreConfig($path, $storeId = NULL){
        if($storeId != NULL){
            return $this->scopeConfig->getValue($path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
        }
        return $this->scopeConfig->getValue($path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->getStore()->getId());
    }

    public function getStore(){
        return $this->_storeManager->getStore();
    }

    public function checkProductIsNew($_product = null) {
        $from_date = $_product->getNewsFromDate();
        $to_date = $_product->getNewsToDate();
        $is_new = false;
        $today = strtotime("now");
        if ($from_date && $to_date) {
            $from_date = strtotime($from_date);
            $to_date = strtotime($to_date);
            if ($from_date <= $today && $to_date >= $today) {
                $is_new = true;
            }
        }
        elseif ($from_date && !$to_date) {
            $from_date = strtotime($from_date);
            if ($from_date <= $today) {
                $is_new = true;
            }
        }elseif (!$from_date && $to_date) {
            $to_date = strtotime($to_date);
            if ($to_date >= $today) {
                $is_new = true;
            }
        }
        return $is_new;
    }
    public function checkProductIsSale($_product = null){
        $specialprice = $_product->getSpecialPrice();
        $oldPrice = $_product->getPrice();

        $specialPriceFromDate = $_product->getSpecialFromDate();
        $specialPriceToDate = $_product->getSpecialToDate();
        $today =  time();
        if ($specialprice < $oldPrice && $specialprice) {
            if($today >= strtotime( $specialPriceFromDate) && $today <= strtotime($specialPriceToDate) || $today >= strtotime( $specialPriceFromDate) && is_null($specialPriceToDate)) {
                return true;
            }
        }
        return false;
    }

    public function checkCountDownTimer($_product = null){
        $specialPriceToDate = $_product->getSpecialToDate();
        $today =  time();
        $res = array();
        $res['isCountDown'] = false;
        if ($specialPriceToDate && $today <= strtotime($specialPriceToDate)){
            $res['time'] = $specialPriceToDate;
            $res['isCountDown'] = true;
        }
        return $res;
    }
    public function getFonts() {
        return [
            ['import-name' => 'PT+Serif', 'font-name' => __('PT Serif')],
            ['import-name' => 'Droid+Serif', 'font-name' => __('Droid Serif')],
            ['import-name' => 'Josefin+Slab', 'font-name' => __('Josefin Slab')],
            ['import-name' => 'Montserrat', 'font-name' => __('Montserrat')],
            ['import-name' => 'Ubuntu', 'font-name' => __('Ubuntu')],
            ['import-name' => 'Titillium+Web', 'font-name' => __('Titillium Web')],
            ['import-name' => 'Noto+Sans', 'font-name' => __('Noto Sans')],
            ['import-name' => 'Lora', 'font-name' => __('Lora')],
            ['import-name' => 'Playfair+Display', 'font-name' => __('Playfair Display')],
            ['import-name' => 'Bree+Serif', 'font-name' => __('Bree Serif')],
            ['import-name' => 'Vollkorn', 'font-name' => __('Vollkorn')],
            ['import-name' => 'Alegreya', 'font-name' => __('Alegreya')],
            ['import-name' => 'Noto+Serif', 'font-name' => __('Noto Serif')],
            ['import-name' => 'Poppins', 'font-name' => __('Poppins')],
            ['import-name' => 'Libre+Baskerville', 'font-name' => __('Libre Baskerville')],
            ['import-name' => 'Lato', 'font-name' => __('Lato')],
            ['import-name' => 'Open+Sans', 'font-name' => __('Open Sans')],
            ['import-name' => 'Roboto', 'font-name' => __('Roboto')],
            ['import-name' => 'Roboto+Slab', 'font-name' => __('Roboto Slab')],
            ['import-name' => 'Oswald', 'font-name' => __('Oswald')],
            ['import-name' => 'Source+Sans+Pro', 'font-name' => __('Source Sans Pro')],
            ['import-name' => 'PT+Sans', 'font-name' => __('PT Sans')]
        ];
    }

    public function getCssNameByCode($code){
        $fonts = $this->getFonts();
        foreach ($fonts as $font){
            if($font['import-name'] == $code){
                return $font['font-name'];
            }
        }
        return false;
    }
    public function getCustomlogo(){
        $path =  $this->getStoreConfig('design_panel/header/custom_logo');
        $baseUrl = $this->urlBuilder->getUrl('', ['_secure' => true]);
        $url = $baseUrl .'pub/media/drinksandco/logo/'.$path;
        return $url;
    }
    
    public function getCategoryCollection($isActive = true, $level = false, $sortBy = false, $pageSize = false)
    {
        $collection = $this->_categoryCollectionFactory->create();
        $collection->addAttributeToSelect('*');

        // select only active categories
        if ($isActive) {
            $collection->addIsActiveFilter();
        }

        // select categories of certain level
        if ($level) {
            $collection->addLevelFilter($level);
        }

        // sort categories by some value
        if ($sortBy) {
            $collection->addOrderField($sortBy);
        }

        // select certain number of categories
        if ($pageSize) {
            $collection->setPageSize($pageSize);
        }
        return $collection;
    }
    public function getCategoryNameByProductId($product){
        $categoryName = '';
        $categoriesId = $product->getCategoryIds();
        if ($categoriesId){
            $categories = $this->getCategoryCollection()
                ->addAttributeToFilter('entity_id', $categoriesId);
            foreach ($categories as $cate){
                $categoryName = $cate->getName();
                if ($cate->getName()){
                    $categoryName = $cate->getName();
                }
            }
        }
        return $categoryName;
    }
    public function getCountProductsInCategory($cateId){
        $collection = $this->_productCollectionFactory->create();
            $collection->joinField(
                'category_id', $this->_resource->getTableName('catalog_category_product'), 'category_id',
                'product_id = entity_id', null, 'left'
            )
                ->addAttributeToFilter('category_id', ['eq'=> $cateId]);
        $collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());

        $collection->addStoreFilter($this->getStore()->getId());
        if (count($collection) > 0){
            return count($collection);
        }
        return false;
    }
}