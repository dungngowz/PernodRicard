<?php

namespace DrinksAndCo\Customer\Controller\Auth;

use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Customer\Model\AddressFactory;
use Magento\Customer\Model\Session;

class ProfileEdit extends \Magento\Framework\App\Action\Action{
    protected $_pageFactory;
    protected $resultJsonFactory;
    protected $addressFactory;
    protected $addresss;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $session;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        JsonFactory $resultJsonFactory,
        AddressFactory $addressFactory,
        \Magento\Customer\Model\AddressFactory $addresss,
        Session $session
    ){
        $this->_pageFactory = $pageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->addressFactory   = $addressFactory;
        $this->session = $session;
        $this->addresss = $addresss;
        return parent::__construct($context);
    }

    public function execute(){

        $resultJson = $this->resultJsonFactory->create();
        
        $customer = $this->session->getCustomer();

        if(!$customer){
            $response = ['code' => 500, 'msg' => __('You are not logged in') ];
            $resultJson->setData($response); return $resultJson;
        }

        $params = $this->getRequest()->getPost();

        list($firstname, $lastname) = $this->splitName($params['fullname']);

        $lastname = $lastname ? $lastname : ' ';

        $customer->setFirstname($firstname)->setLastname($lastname);

        $password = trim($params['password']);
        if(!empty($password)){
            $customer->changePassword($password);
        }

        //b2bFirstLogin
        if(isset($params['company_name'])){
            $customerData = $customer->setCompany_name(trim($params['company_name']));
            $customerData = $customer->setTax_code(trim($params['tax_code']));
        }

        if(!empty($params['password'])){
            $customer->setPassword(trim($params['password'])); 
        }

        $customer->save();

        //Update Address + Region
        $address = null;
        if($customer->getAddresses()){
            foreach ($customer->getAddresses() as $k => $item){
                $address = $item;
                break;
            }

            $address->setFirstname($firstname)
                ->setLastname($lastname)
                ->setStreet($params['address'])
                ->setTelephone($params['phone'])
                ->setRegionId($params['region']);
                
        }else{
            $address = $this->addresss->create();

            $address->setCustomerId($customer->getId())
                ->setFirstname($firstname)
                ->setLastname($lastname)
                ->setCountryId('VN')
                ->setPostcode(10000)
                ->setRegionId($params['region'])
                ->setTelephone($params['phone'])
                ->setFax('-')
                ->setCompany('-')
                ->setStreet($params['address'])
                ->setIsDefaultBilling('1')
                ->setIsDefaultShipping('1')
                ->setSaveInAddressBook('1');
        }

        $address->save();

        $response = ['code' => 200, 'msg' => __('Your information has been updated successfully') ];
        $resultJson->setData($response); return $resultJson;
    }

    public function splitName($name) {
        $name = trim($name);
        $last_name = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
        $first_name = trim( preg_replace('#'.$last_name.'#', '', $name ) );
        return array($first_name, $last_name);
    }
}