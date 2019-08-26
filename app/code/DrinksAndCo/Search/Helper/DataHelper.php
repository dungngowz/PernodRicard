<?php
namespace DrinksAndCo\Search\Helper;

use Magento\Framework\App\Helper\Context;

class DataHelper extends \Magento\Framework\App\Helper\AbstractHelper{

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
        return $this->getStoreConfig('search/' . $key);
    }

    public function getBaseMediaUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
    }

    public function getRoute()
    {   
        return $this->_storeManager->getStore()->getBaseUrl();
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
