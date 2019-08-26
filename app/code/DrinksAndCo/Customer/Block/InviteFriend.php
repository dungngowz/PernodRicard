<?php
namespace DrinksAndCo\Customer\Block;

class InviteFriend extends \Magento\Framework\View\Element\Template
{
    /**
     * @var int
     */
    private $_username = -1;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var \Magento\Customer\Model\Url
     */
    protected $_customerUrl;

    /**
     * @var \Magento\Directory\Block\Data
     */
    protected $directoryBlock;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Customer\Model\Url $customerUrl
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Customer\Model\Url $customerUrl,
        \Magento\Directory\Block\Data $directoryBlock,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_customerUrl = $customerUrl;
        $this->_customerSession = $customerSession;
        $this->directoryBlock = $directoryBlock;
    }

    /**
     * Check if autocomplete is disabled on storefront
     *
     * @return bool
     */
    public function isAutocompleteDisabled()
    {
        return (bool)!$this->_scopeConfig->getValue(
            \Magento\Customer\Model\Form::XML_PATH_ENABLE_AUTOCOMPLETE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Returns action url for contact form
     *
     * @return string
     */
    public function getPostActionUrl()
    {
        return $this->getUrl('dccustomer/invite/post', ['_secure' => true]);
    }

    /**
     * Returns list html region
     *
     * @return string
     */
    public function getRegion()
    {
        $region = $this->directoryBlock->getRegionHtmlSelect();
        return $region;
    }
}