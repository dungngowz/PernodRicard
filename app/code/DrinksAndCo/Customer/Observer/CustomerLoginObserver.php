<?php
namespace DrinksAndCo\Customer\Observer;

use Magento\Framework\Event\ObserverInterface;
use DrinksAndCo\Customer\Helper\Email;

class CustomerLoginObserver implements ObserverInterface
{
    private $helperEmail;

    public function __construct(Email $helperEmail) {
        $this->helperEmail = $helperEmail;
    }

    public function execute(\Magento\Framework\Event\Observer $observer){
        return $this->helperEmail->sendEmail();
    }
}