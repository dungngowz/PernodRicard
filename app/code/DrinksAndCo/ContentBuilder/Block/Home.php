<?php
namespace DrinksAndCo\ContentBuilder\Block;

class Home extends \Magento\Framework\View\Element\Template
{
    protected $_postNews;
    protected $_postNewsCollection ;

    protected $_postHistory;
    protected $_postHistoryCollection ;
    protected $_commonHelper;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \DrinksAndCo\News\Model\Post $postNews,
        \DrinksAndCo\Base\Helper\Common $commonHelper,
        \DrinksAndCo\History\Model\Post $postHistory
    )
    {
        $this->_postNews = $postNews ;
        $this->_postHistory = $postHistory ;
        $this->_commonHelper = $commonHelper;
        parent::__construct($context);
    }

    public function _construct()
    {
        parent::_construct();

        $postNews = $this->_postNews;
        $postNewsCollection = $postNews->getCollection()
            ->addFieldToFilter('status', 1)
            ->addStoreFilter($this->_storeManager->getStore()->getId())
            ->setOrder('created_at', $this->getConfig('general_settings/default_sort'));

        $post_per_page = 9 ;
        $postNewsCollection->setPageSize($post_per_page);
        // $postNewsCollection->setCurPage(1);

        $this->_postNewsCollection = $postNewsCollection ;

        $postHistory = $this->_postHistory;
        $postHistoryCollection = $postHistory->getCollection()
            ->addFieldToFilter('status', 1)
            ->addStoreFilter($this->_storeManager->getStore()->getId())
            ->setOrder('created_at', $this->getConfig('general_settings/default_sort'));
        $this->_postHistoryCollection = $postHistoryCollection ;
    }

    public function getNewsCollection() {
        return $this->_postNewsCollection ;
    }

    public function getHistoryCollection() {
        return $this->_postHistoryCollection ;
    }

    public function shorter($text, $chars_limit) {
        if (strlen($text) > $chars_limit) 
            return substr($text, 0, strrpos(substr($text, 0, $chars_limit), " ")).'...';
        else return $text;
    }

    public function isFirstLogin(){
        return $this->_commonHelper->isFirstLogin();
    }
}