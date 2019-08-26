<?php

/**
 * Data.php
 *
 * @copyright Copyright © 1989 Staempfli. All rights reserved.
 * @author    dung.ngowz@gmail.com
 */

namespace DrinksAndCo\Search\Model;

use Magento\Framework\DataObject\IdentityInterface;

use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\Model\AbstractModel;
use Magento\Store\Model\StoreManagerInterface;
use DrinksAndCo\Search\Helper\DataHelper;
use DrinksAndCo\Search\Model\ResourceModel\Data as DataResource;
use DrinksAndCo\Search\Model\ResourceModel\Data\Collection;

class Data extends AbstractModel
{
    protected $searchHelper ;
    protected $storeManager;
    protected $_imageBuilder;
    protected $_commonHelper;
    /**
     * CMS page cache tag
     */
    const CACHE_TAG = 'drinksandco_search_data';

    /**
     * @var string
     */
    protected $_cacheTag = 'drinksandco_search_data';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'drinksandco_search_data';

    public function __construct(
        Context $context,
        Registry $registry,
        StoreManagerInterface $storeManager,
        DataResource $resource = null,
        Collection $resourceCollection = null,
        DataHelper $searchHelper,
        \Magento\Catalog\Block\Product\ImageBuilder $imageBuilder,
        \DrinksAndCo\Base\Helper\Common $commonHelper,        
        array $data = []
    )
    {
        $this->storeManager = $storeManager;
        $this->searchHelper = $searchHelper;
        $this->_imageBuilder = $imageBuilder;
        $this->_commonHelper = $commonHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct(
    )
    {
        parent::_construct();
        $this->_init('DrinksAndCo\Search\Model\ResourceModel\Data');
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Save from collection data
     *
     * @param array $data
     * @return $this|bool
     */
    public function saveCollection(array $data)
    {
        if (isset($data[$this->getId()])) {
            $this->addData($data[$this->getId()]);
            $this->getResource()->save($this);
        }
        return $this;
    }

    public function getPostUrl()
    {
        $link = '' ;
        $route = $this->searchHelper->getRoute();
        if($this->getType() == 'product')
            $link = $route . $this->getUrl() . '.html';
        else if($this->getType() == 'news' )
            $link = $route .'news'.'/' . $this->getUrl();
        else if($this->getType() == 'history' )
            $link = $route .'history'.'/' . $this->getUrl();
        return  $link ;
    }

    public function getThumbnailUrl()
    {
        $url = false;
        $image = $this->getThumbnail();
        if ($image) {
            $url = $this->storeManager->getStore()->getBaseUrl(
                    \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                ) . $image;
        }
        
        return $url;
    }

    public function getThumbnailProduct(){
        $product = $this->_commonHelper->getDetailProductById($this->getObjectId());
        if($product){
            return $this->getImage($product, 'product_small_image')->getImageUrl();
        }

        return "";
    }

    public function getImage($product, $imageId, $attributes = []){
        return $this->_imageBuilder->setProduct($product)
            ->setImageId($imageId)
            ->setAttributes($attributes)
            ->create();
    }
}
