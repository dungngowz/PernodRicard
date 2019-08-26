<?php
namespace DrinksAndCo\Experience\Helper;

use Magento\Framework\App\Helper\Context;

class Data extends \Magento\Framework\App\Helper\AbstractHelper{

    protected $_storeManager;
    protected $_objectManager;

    public function __construct(
        Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\ObjectManagerInterface $objectManager
    )
    {
        $this->_objectManager = $objectManager;
        $this->_storeManager = $storeManager;
        parent::__construct($context);
    }

    public function getConfig($key, $store = null)
    {
        return $this->getStoreConfig('experience/' . $key);
    }

    public function getBaseMediaUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
    }

    public function getRoute()
    {
        $route = $this->getConfig('general_settings/route');
        if ($this->getConfig('general_settings/route') == '') {
            $route = 'experience';
        }
        return $this->_storeManager->getStore()->getBaseUrl() . $route;
    }
    public function getStore(){
        return $this->_storeManager->getStore();
    }
    public function getStoreConfig($node, $storeId = NULL){
        if($storeId != NULL){
            return $this->scopeConfig->getValue($node, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
        }
        return $this->scopeConfig->getValue($node, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->getStore()->getId());
    }

    public function getTagUrl($tag)
    {
        $route = $this->getConfig('general_settings/route');
        if ($this->getConfig('general_settings/route') == '') {
            $route = 'experience';
        }
        return $this->_storeManager->getStore()->getBaseUrl() . $route . '/tag/' . urlencode($tag);
    }

    public function convertSlashes($tag, $direction = 'back')
    {
        if ($direction == 'forward') {
            $tag = preg_replace("#/#is", "&#47;", $tag);
            $tag = preg_replace("#\\\#is", "&#92;", $tag);
            return $tag;
        }
        $tag = str_replace("&#47;", "/", $tag);
        $tag = str_replace("&#92;", "\\", $tag);
        return $tag;
    }

    public function checkLoggedIn()
    {
        return $this->_objectManager->get('Magento\Customer\Model\Session')->isLoggedIn();
    }


    public function getPostUrl($post) {
        $store = $this->_storeManager->getStore()->getCode();

        if($store){
            $url = $post->getPostUrlWithNoCategory() . '?___store=' . $store;
        }else{
            $url = $post->getPostUrlWithNoCategory();
        }

        return $url;
    }


}
