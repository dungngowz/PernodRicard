<?php
namespace DrinksAndCo\CustomizeOrder\Block;

class Confirm extends \Magento\Framework\View\Element\Template
{
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Customer\Model\Url $customerUrl,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_customerUrl = $customerUrl;
        $this->_customerSession = $customerSession;
    }

    public function getInfomationConfirmUrl(){
        return trim($this->getUrl(__('information-confirm')));
    }
}
