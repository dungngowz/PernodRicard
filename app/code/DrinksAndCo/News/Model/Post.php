<?php

namespace DrinksAndCo\News\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Store\Model\StoreManagerInterface;
use DrinksAndCo\News\Model\Resource\Post as PostResource;
use DrinksAndCo\News\Model\Resource\Post\Collection;
use DrinksAndCo\News\Helper\Data;
use Magento\Framework\UrlInterface;
use DrinksAndCo\News\Model\Category;
use DrinksAndCo\News\Model\Comment;

class Post extends AbstractModel
{
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 2;

    protected $storeManager;
    protected $newsHelper;

    public function __construct(
        Context $context,
        Registry $registry,
        StoreManagerInterface $storeManager,
        PostResource $resource = null,
        Collection $resourceCollection = null,
        Data $newsHelper,
        Category $category,
        Comment $comment,
        array $data = []
    )
    {
        $this->storeManager = $storeManager;
        $this->newsHelper = $newsHelper;
        $this->category = $category;
        $this->comment = $comment;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    protected function _construct()
    {
        parent::_construct();
        $this->_init('DrinksAndCo\News\Model\Resource\Post');
    }

    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }

    public function getPostUrlWithNoCategory()
    {
        $route = $this->newsHelper->getRoute();
        return $route . '/' . $this->getUrlKey();
    }

    public function getPostUrlWithCategory($categoryId)
    {
        $route = $this->newsHelper->getRoute();
        return $route . '/' . $this->category->load($categoryId)->getUrlKey() . '/' . $this->getUrlKey();
    }

    public function getThumbnailUrl()
    {
        $url = $this->storeManager->getStore()->getBaseUrl(
                UrlInterface::URL_TYPE_MEDIA
            ) . 'ecg/news/no_image.png';
        $thumbnail = $this->getThumbnail();
        if ($thumbnail) {
            $url = $this->storeManager->getStore()->getBaseUrl(
                    UrlInterface::URL_TYPE_MEDIA
                ) . $thumbnail;
        };
        return $url;
    }

    public function getImageUrl()
    {
        $url = false;
        $image = $this->getImage();
        if ($image) {
            $url = $this->storeManager->getStore()->getBaseUrl(
                    \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                ) . $image;
        };
        return $url;
    }

    public function getRelatedImageUrl()
    {
        $url = false;
        $image = $this->getRelatedImage();
        if ($image) {
            $url = $this->storeManager->getStore()->getBaseUrl(
                    \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                ) . $image;
        };
        return $url;
    }

    public function setCreatedAt($createdAt)
    {
        return $this->setData('created_at', $createdAt);
    }

    public function setUpdatedAt($updatedAt)
    {
        return $this->setData('updated_at', $updatedAt);
    }

    public function getCreatedAt()
    {
        return $this->getData('created_at');
    }

    public function getUpdatedAt()
    {
        return $this->getData('updated_at');
    }

    public function getCatetories()
    {
        $catetories = $this->category->getCollection()
            ->addPostFilter($this->getId())
            ->addStoreFilter($this->storeManager->getStore()->getId());
        return $catetories;
    }

    public function getCommentCount()
    {
        $comments = $this->comment->getCollection()
            ->addFieldToFilter('post_id', ['eq' => $this->getId()])
			->addFieldToFilter('status', ['eq' => 1]);
        return count($comments);
    }
    public function getLabelHtml(){
        $html = '';
        $label = $this->getData('label');
        $color = $this->getData('color');
        if ($label && $color){
            $html .= '<div class="label" style="background: '.$color.'">';
            $html .= $label;
            $html .= '</div>';
        }
        return $html;
    }
}
