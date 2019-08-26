<?php

namespace DrinksAndCo\Search\Observer;

use Magento\Framework\Event\ObserverInterface;

class IndexHistorySearch implements ObserverInterface
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
        $history = $observer->getData('history_post');
        if($history->getId()){
            $historyId = $history->getId();
            $dataFactory = $this->_dataSearchFactory->create();

            $dataCollection = $dataFactory->getCollection()->addFieldToFilter('object_id', $historyId)->getFirstItem();

            if($dataCollection){
                $dataSearch = $dataCollection;
            }else{
                $dataSearch = $dataFactory;
            }

            $title = $history->getTitle();
            $url = $history->getUrlKey() ;
            $thumbnail = $history->getThumbnail() ;
            $short_content = $history->getShortCotent() ;
            $content = $history->getContent();


            $dataSearch->setType('history')
                ->setTitle($title)
                ->setObjectId($historyId)
                ->setUrl($url)
                ->setThumbnail($thumbnail)
                ->setShortContent($short_content)
                ->setContent($content)
                ->save();
        }
    }
}