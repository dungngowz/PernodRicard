<?php

namespace DrinksAndCo\Search\Observer;

use Magento\Framework\Event\ObserverInterface;

class IndexProductSearch implements ObserverInterface
{
    protected $_options;
    protected $_dataSearchFactory;
    protected $_storeManager;

    public function __construct(
        \Magento\Catalog\Model\Product\Option $options,
        \DrinksAndCo\Search\Model\DataFactory $dataSearchFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->_options = $options;
        $this->_dataSearchFactory = $dataSearchFactory;
        $this->_storeManager = $storeManager;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $product = $observer->getProduct();

        if($product->getId()){
            $productId = $product->getId();
            $dataFactory = $this->_dataSearchFactory->create();

            $dataCollection = $dataFactory->getCollection()->addFieldToFilter('object_id', $productId)->getFirstItem();

            if($dataCollection){
                $dataSearch = $dataCollection;
            }else{
                $dataSearch = $dataFactory;
            }
            $url = $product->getUrlKey() ;
            $thumbnail = $product->getThumbnail() ;
            $short_content = $product->getShortDescription() ;
            $content = $product->getDescription();


            $dataSearch->setType('product')
                ->setTitle($product->getName())
                ->setObjectId($productId)
                ->setUrl($url)
                ->setThumbnail($thumbnail)
                ->setShortContent($short_content)
                ->setContent($content)
                ->save();
        }
    }
}