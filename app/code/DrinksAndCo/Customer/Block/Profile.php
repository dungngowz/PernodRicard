<?php
namespace DrinksAndCo\Customer\Block;
class Profile extends \Magento\Framework\View\Element\Template
{

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Magento\Customer\Model\Url
     */
    protected $_customerUrl;

    /**
     * @var \Magento\Directory\Block\Data
     */
    protected $directoryBlock;

    protected $orderModel;
    protected $_productFactory;
    protected $_categoryFactory;
    protected $_imageBuilder;
    protected $timezone;
    protected $_commonHelper;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Customer\Model\Url $customerUrl
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Customer\Model\Url $customerUrl,
        \Magento\Directory\Block\Data $directoryBlock,
        \Magento\Sales\Model\Order $orderModel,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Catalog\Block\Product\ImageBuilder $imageBuilder,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \DrinksAndCo\Base\Helper\Common $commonHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_customerUrl = $customerUrl;
        $this->customerSession = $customerSession;
        $this->directoryBlock = $directoryBlock;
        $this->orderModel = $orderModel;
        $this->_productFactory = $productFactory;
        $this->_categoryFactory = $categoryFactory;
        $this->_imageBuilder = $imageBuilder;
        $this->timezone = $timezone;
        $this->_commonHelper = $commonHelper;
    }

    /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        $this->pageConfig->getTitle()->set(__('Profile'));
        return parent::_prepareLayout();
    }

    /**
     * Returns action url for contact form
     *
     * @return string
     */
    public function getPostActionUrl()
    {
        return $this->getUrl('dccustomer/login/submitlogin', ['_secure' => true]);
    }

    /**
     * Returns list html region
     *
     * @return string
     */
    public function getRegions($regionId)
    {
        $region = $this->directoryBlock->setRegionId($regionId)->getRegionHtmlSelect();
        return $region;
    }

    public function getUpdateProfileUrl(){
        return $this->getUrl('dccustomer/auth/profileedit', ['_secure' => true]);
    }

    public function getCurrentCustomer(){
        if ( $this->customerSession->isLoggedIn()){
            return $this->customerSession->getCustomer();
        }
        return false;
    }

    public function formatDateStore($date){
        return $this->timezone->formatDate($date, \IntlDateFormatter::MEDIUM, false);

        var_dump($code);

        exit();

        return date_format(date_create($date),"Y/m/d");
    }

    public function getLatestOrders(){
        if ( $this->customerSession->isLoggedIn()){
            $customer_email = $this->customerSession->getCustomer()->getEmail();
            $collections = $this->orderModel->getCollection()->addAttributeToFilter('customer_email', $customer_email)->setOrder('entity_id','DESC');
            return $collections->count() ? $collections : false;
        }

        return [];
    }

    public function getCodeOrder($id){
        return 'MTS-' . str_pad($id, 5, "0", STR_PAD_LEFT);
    }

    public function getDetailProduct($sku = ''){
        $sku = $sku ? $sku : $this->_request->getParam('path');
        $product = $this->_productFactory->create()->loadByAttribute('sku', $sku);
        return $product;
    }

    public function getUrlDetailProduct($productId){
        $product = $this->_commonHelper->getDetailProductById($productId);

        if($product){
            return $product->getProductUrl();
        }

        return $this->getUrl('');
    }

    public function getImage($sku, $imageId, $attributes = []){
        $product = $this->getDetailProduct($sku);

        if(is_bool($product)){
            return '';
        }

        return $this->_imageBuilder->setProduct($product)
            ->setImageId($imageId)
            ->setAttributes($attributes)
            ->create()->getImageUrl();
        
    }

    public function isB2B(){
        return $this->_commonHelper->isB2B();
    }
}