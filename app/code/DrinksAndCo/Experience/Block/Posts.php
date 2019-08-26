<?php

namespace DrinksAndCo\Experience\Block;

use Magento\Customer\Model\Context as CustomerContext;

class Posts extends \Magento\Framework\View\Element\Template
{
    protected $_coreRegistry = null;
    protected $_experienceHelper;
    protected $_post;
    protected $_category;
    protected $httpContext;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \DrinksAndCo\Experience\Helper\Data $experienceHelper,
        \DrinksAndCo\Experience\Model\Post $post,
        \DrinksAndCo\Experience\Model\Category $category,
        \Magento\Framework\App\Http\Context $httpContext,
        array $data = []
    )
    {
        $this->_post = $post;
        $this->_category = $category;
        $this->_coreRegistry = $registry;
        $this->_experienceHelper = $experienceHelper;
        $this->httpContext = $httpContext;
        parent::__construct($context, $data);
    }

    public function _construct()
    {
        if (!$this->getConfig('general_settings/enabled')) return;
        parent::_construct();
        $post = $this->_post;
        $postCollection = $post->getCollection()
            ->addFieldToFilter('status', 1)
            ->addStoreFilter($this->_storeManager->getStore()->getId())
            ->setOrder('created_at', $this->getConfig('general_settings/default_sort'));
        $this->setCollection($postCollection);
    }

    public function getCacheKeyInfo()
    {
        return [
            'BLOG_POST_LIST',
            $this->_storeManager->getStore()->getId(),
            $this->_design->getDesignTheme()->getId(),
            $this->httpContext->getValue(CustomerContext::CONTEXT_GROUP),
            'template' => $this->getTemplate()
        ];
    }

    protected function _addBreadcrumbs()
    {
        $breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs');
        $baseUrl = $this->_storeManager->getStore()->getBaseUrl();
        $pageTitle = $this->_experienceHelper->getConfig('general_settings/title');
        $breadcrumbsBlock->addCrumb(
            'home',
            [
                'label' => __('Home'),
                'title' => __('Go to Home Page'),
                'link' => $baseUrl
            ]
        );
        $breadcrumbsBlock->addCrumb(
            'experience',
            [
                'label' => $pageTitle,
                'title' => $pageTitle,
                'link' => ''
            ]
        );
    }

    public function setCollection($collection)
    {
        $this->_collection = $collection;
        return $this->_collection;
    }

    public function getCollection()
    {
        return $this->_collection;
    }

    public function getConfig($key, $default = '')
    {
        $result = $this->_experienceHelper->getConfig($key);
        if (!$result) {
            return $default;
        }
        return $result;
    }

    protected function _prepareLayout()
    {
        $pageTitle = $this->getConfig('general_settings/title');
        $metaKeywords = $this->getConfig('general_settings/meta_keywords');
        $metaDescription = $this->getConfig('general_settings/meta_description');
        // $this->_addBreadcrumbs();
        $this->pageConfig->addBodyClass('experience-post-list');
        if ($pageTitle) {
            $this->pageConfig->getTitle()->set($pageTitle);
        }
        if ($metaKeywords) {
            $this->pageConfig->setKeywords($metaKeywords);
        }
        if ($metaDescription) {
            $this->pageConfig->setDescription($metaDescription);
        }
        if ($this->getCollection()) {
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'experience.post.list.pager'
            );
            $pager->setLimit($this->getConfig('general_settings/posts_per_page'))->setCollection(
                $this->getCollection()
            );
            $this->setChild('pager', $pager);
        }
        return parent::_prepareLayout();
    }

    public function getParentCategory($postId){
        $postCollection = $this->_category->getCollection()
                                ->addFieldToFilter('status', 1)
                                ->addPostFilter($postId);
        return $postCollection;
    }
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
    
    public function getCategoryUrl($urlKey)
    {
        $route = $this->_experienceHelper->getRoute();
        return $route . '/' . $urlKey;
    }
}