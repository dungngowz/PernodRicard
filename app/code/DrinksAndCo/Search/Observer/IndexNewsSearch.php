<?php

namespace DrinksAndCo\Search\Observer;

use Magento\Framework\Event\ObserverInterface;

class IndexNewsSearch implements ObserverInterface
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
        $news = $observer->getData('news_post');
        if($news->getId()){
            $newsId = $news->getId();
            $dataFactory = $this->_dataSearchFactory->create();

            $dataCollection = $dataFactory->getCollection()->addFieldToFilter('object_id', $newsId)->getFirstItem();

            if($dataCollection){
                $dataSearch = $dataCollection;
            }else{
                $dataSearch = $dataFactory;
            }

            $title = $news->getTitle();
            $url = $news->getUrlKey() ;
            $thumbnail = $news->getThumbnail() ;
            $short_content = $news->getShortCotent() ;
            $content = $news->getContent();


            $dataSearch->setType('news')
                ->setTitle($title)
                ->setObjectId($newsId)
                ->setUrl($url)
                ->setThumbnail($thumbnail)
                ->setShortContent($short_content)
                ->setContent($content)
                ->save();
        }
    }
}