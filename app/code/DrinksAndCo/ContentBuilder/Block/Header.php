<?php
namespace DrinksAndCo\ContentBuilder\Block;

class Header extends \Magento\Framework\View\Element\Template
{
    public $_logo;
    public $commonHelper;
    protected $_store;
    protected $_request;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Theme\Block\Html\Header\Logo $logo,
        \DrinksAndCo\Base\Helper\Common $commonHelper,
        \Magento\Store\Api\Data\StoreInterface $store,
        \Magento\Framework\App\Request\Http $request
    ){
        parent::__construct($context);
        $this->_logo = $logo;
        $this->commonHelper = $commonHelper;
        $this->_store = $store;
        $this->_request = $request;
    }

    public function getLocaleCode()
    {
        return $this->_store->getLocaleCode();
    }

    public function isClassHomePage(){
        return $this->_logo->isHomePage() ? 'home' : '';
    }

    public function isLogoHomePage(){
        return $this->_logo->isHomePage();
    }

    public function getUrlSearch(){
        return $this->getUrl('customsearch');
    }

    public function getKeyword(){
        return $this->_request->getParam('keyword');
    }

    public function isActiveMenu($key){
        $uriPath = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        $segments = explode('/', $uriPath);

        if(count($segments) <= 1){
            return '';
        }

        $menus = [
            'product' => [ __('url-products'), __('url-products').'.html', __('detail-product') ],
            'experiences' => [ __('url-experiences'), __('url-experiences').'.html', __('detail-experience') ],
            'news' => [ __('news') ],
            'history' => [ __('history')],
            'contact' => [ __('contact')],
        ];

        if(in_array($segments[1], $menus[$key] )){
            return 'active';
        }

        return '';
    }
}