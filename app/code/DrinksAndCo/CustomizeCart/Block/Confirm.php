<?php
namespace DrinksAndCo\CustomizeCart\Block;

/**
 * Shopping cart block
 *
 * @api
 * @since 100.0.2
 */
class Confirm extends \Magento\Checkout\Block\Cart\AbstractCart
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Url
     */
    protected $_catalogUrlBuilder;

    /**
     * @var \Magento\Framework\App\Http\Context
     */
    protected $httpContext;

    /**
     * @var \Magento\Checkout\Helper\Cart
     */
    protected $_cartHelper;

    /**
     * @var \Magento\Directory\Block\Data
     */
    protected $directoryBlock;

    /**
     * @var \DrinksAndCo\Base\Helper\Common
     */
    protected $commonHelper;
    protected $_customerSession;

    protected $_imageBuilder;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Catalog\Model\ResourceModel\Url $catalogUrlBuilder
     * @param \Magento\Checkout\Helper\Cart $cartHelper
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param array $data
     * @codeCoverageIgnore
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Catalog\Model\ResourceModel\Url $catalogUrlBuilder,
        \Magento\Checkout\Helper\Cart $cartHelper,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Directory\Block\Data $directoryBlock,
        \DrinksAndCo\Base\Helper\Common $commonHelper,
        \Magento\Catalog\Block\Product\ImageBuilder $imageBuilder,
        array $data = []
    ) {
        $this->_cartHelper = $cartHelper;
        $this->_catalogUrlBuilder = $catalogUrlBuilder;
        $this->_isScopePrivate = true;
        $this->httpContext = $httpContext;
        $this->directoryBlock = $directoryBlock;
        $this->commonHelper = $commonHelper;
        $this->_imageBuilder = $imageBuilder;
        $this->_customerSession = $customerSession;
        parent::__construct($context, $customerSession, $checkoutSession, $data);
    }

    /**
     * Prepare Quote Item Product URLs
     *
     * @codeCoverageIgnore
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->prepareItemUrls();
    }

    /**
     * prepare cart items URLs
     *
     * @return void
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function prepareItemUrls()
    {
        $products = [];
        /* @var $item \Magento\Quote\Model\Quote\Item */
        foreach ($this->getItems() as $item) {
            $product = $item->getProduct();
            $option = $item->getOptionByCode('product_type');
            if ($option) {
                $product = $option->getProduct();
            }

            if ($item->getStoreId() != $this->_storeManager->getStore()->getId() &&
                !$item->getRedirectUrl() &&
                !$product->isVisibleInSiteVisibility()
            ) {
                $products[$product->getId()] = $item->getStoreId();
            }
        }

        if ($products) {
            $products = $this->_catalogUrlBuilder->getRewriteByProductStore($products);
            foreach ($this->getItems() as $item) {
                $product = $item->getProduct();
                $option = $item->getOptionByCode('product_type');
                if ($option) {
                    $product = $option->getProduct();
                }

                if (isset($products[$product->getId()])) {
                    $object = new \Magento\Framework\DataObject($products[$product->getId()]);
                    $item->getProduct()->setUrlDataObject($object);
                }
            }
        }
    }

    /**
     * @codeCoverageIgnore
     * @return bool
     */
    public function hasError()
    {
        return $this->getQuote()->getHasError();
    }

    public function getGrandTotal(){
        return $this->getQuote()->getGrandTotal();
    }

    public function getSubTotal(){
        return $this->getQuote()->getSubtotal();
    }

    /**
     * @codeCoverageIgnore
     * @return int
     */
    public function getItemsSummaryQty()
    {
        return $this->getQuote()->getItemsSummaryQty();
    }

    /**
     * @codeCoverageIgnore
     * @return int
     */
    public function getAllItems()
    {
        $this->getQuote()->collectTotals();
        return $this->getQuote()->getAllItems();
    }

    /**
     * @codeCoverageIgnore
     * @return bool
     */
    public function isWishlistActive()
    {
        $isActive = $this->_getData('is_wishlist_active');
        if ($isActive === null) {
            $isActive = $this->_scopeConfig->getValue(
                'wishlist/general/active',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            ) && $this->httpContext->getValue(
                Context::CONTEXT_AUTH
            );
            $this->setIsWishlistActive($isActive);
        }
        return $isActive;
    }

    /**
     * @codeCoverageIgnore
     * @return string
     */
    public function getCheckoutUrl()
    {
        return $this->getUrl('checkout', ['_secure' => true]);
    }

    /**
     * @return string
     */
    public function getContinueShoppingUrl()
    {
        $url = $this->getData('continue_shopping_url');
        if ($url === null) {
            $url = $this->_checkoutSession->getContinueShoppingUrl(true);
            if (!$url) {
                $url = $this->_urlBuilder->getUrl();
            }
            $this->setData('continue_shopping_url', $url);
        }
        return $url;
    }

    /**
     * @return bool
     * @codeCoverageIgnore
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getIsVirtual()
    {
        return $this->_cartHelper->getIsVirtualQuote();
    }

    /**
     * Return list of available checkout methods
     *
     * @param string $alias Container block alias in layout
     * @return array
     */
    public function getMethods($alias)
    {
        $childName = $this->getLayout()->getChildName($this->getNameInLayout(), $alias);
        if ($childName) {
            return $this->getLayout()->getChildNames($childName);
        }
        return [];
    }

    /**
     * Return HTML of checkout method (link, button etc.)
     *
     * @param string $name Block name in layout
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getMethodHtml($name)
    {
        $block = $this->getLayout()->getBlock($name);
        if (!$block) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Invalid method: %1', $name));
        }
        return $block->toHtml();
    }

    /**
     * Return customer quote items
     *
     * @return array
     */
    public function getItems()
    {
        if ($this->getCustomItems()) {
            return $this->getCustomItems();
        }

        return parent::getItems();
    }

    /**
     * @codeCoverageIgnore
     * @return int
     */
    public function getItemsCount()
    {
        return $this->getQuote()->getItemsCount();
    }

    public function getTotalQty()
    {
        return $this->getQuote()->getItemsQty();
    }

    /**
     * Render pagination HTML
     *
     * @return string
     * @since 100.1.7
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
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

    public function getUrlDetailProduct($id){
        $product = $this->commonHelper->getDetailProductById($id);
        if(!$product){
            return '';
        }
        return $product->getProductUrl();
    }
    
    public function addZero($num){
        return $num > 9 ? $num : '0'.$num;
    }

    public function getAddOrderUrl(){
        return $this->getUrl('customizeorder/index/add', ['_secure' => true]);
    }

    public function getUpdateCartUrl(){
        return $this->getUrl('customizecart/index/updatetocart', ['_secure' => true]);
    }

    public function getImage($product, $imageId, $attributes = []){
        return $this->_imageBuilder->setProduct($product)
            ->setImageId($imageId)
            ->setAttributes($attributes)
            ->create();
    }

    public function getDetailProduct($skuProduct, $field = false){
        $detailProduct = $this->commonHelper->getDetailProduct($skuProduct);
        if($field == 'thumbnail-cart'){
            return $this->getImage($detailProduct, 'cart_page_product_thumbnail')->getImageUrl();
        }
        return $detailProduct;
    }

    public function getCurrentCustomer(){
        return $this->_customerSession->getCustomer();
    }
}

