<?php

namespace DrinksAndCo\Customer\Controller\Login;

use Magento\Customer\Model\Account\Redirect as AccountRedirect;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Session;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Model\Url as CustomerUrl;
use Magento\Framework\Exception\EmailNotConfirmedException;
use Magento\Framework\Exception\AuthenticationException;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\State\UserLockedException;
use Magento\Framework\App\Config\ScopeConfigInterface;
use DrinksAndCo\Customer\Helper\Data as CustomCooike;

class SubmitLogin extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;

    /** @var AccountManagementInterface */
    protected $customerAccountManagement;

    /** @var Validator */
    protected $formKeyValidator;

    /**
     * @var AccountRedirect
     */
    protected $accountRedirect;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory
     */
    private $cookieMetadataFactory;

    /**
     * @var \Magento\Framework\Stdlib\Cookie\PhpCookieManager
     */
    private $cookieMetadataManager;

    /**
     * @var \DrinksAndCo\Customer\Helper\Data
     */
    private $getCookiedata;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    protected $_commonHelper;

    /**
     * @param Context $context
     * @param Session $customerSession
     * @param AccountManagementInterface $customerAccountManagement
     * @param CustomerUrl $customerHelperData
     * @param Validator $formKeyValidator
     * @param AccountRedirect $accountRedirect
     */

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        Session $customerSession,
        AccountManagementInterface $customerAccountManagement,
        CustomerUrl $customerHelperData,
        Validator $formKeyValidator,
        AccountRedirect $accountRedirect,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        CustomCooike $getCookiedata,
        \DrinksAndCo\Base\Helper\Common $commonHelper
    ){
        $this->_pageFactory = $pageFactory;

        $this->session = $customerSession;
        $this->customerAccountManagement = $customerAccountManagement;
        $this->customerUrl = $customerHelperData;
        $this->formKeyValidator = $formKeyValidator;
        $this->accountRedirect = $accountRedirect;
        $this->getCookiedata = $getCookiedata;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->_commonHelper = $commonHelper;

        parent::__construct($context);
    }

    /**
     * Get scope config
     *
     * @return ScopeConfigInterface
     * @deprecated
     */
    private function getScopeConfig()
    {
        if (!($this->scopeConfig instanceof \Magento\Framework\App\Config\ScopeConfigInterface)) {
            return \Magento\Framework\App\ObjectManager::getInstance()->get(
                \Magento\Framework\App\Config\ScopeConfigInterface::class
            );
        } else {
            return $this->scopeConfig;
        }
    }

    /**
     * Retrieve cookie manager
     *
     * @deprecated
     * @return \Magento\Framework\Stdlib\Cookie\PhpCookieManager
     */
    private function getCookieManager()
    {
        if (!$this->cookieMetadataManager) {
            $this->cookieMetadataManager = \Magento\Framework\App\ObjectManager::getInstance()->get(
                \Magento\Framework\Stdlib\Cookie\PhpCookieManager::class
            );
        }
        return $this->cookieMetadataManager;
    }

    /**
     * Retrieve cookie metadata factory
     *
     * @deprecated
     * @return \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory
     */
    private function getCookieMetadataFactory()
    {
        if (!$this->cookieMetadataFactory) {
            $this->cookieMetadataFactory = \Magento\Framework\App\ObjectManager::getInstance()->get(
                \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory::class
            );
        }
        return $this->cookieMetadataFactory;
    }

    /**
     * Login post action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();

        if ($this->getRequest()->isPost()) {
            $login = $this->getRequest()->getPost('login');

            //b2bFirstLogin
            if(!empty($login['company_name'])){
                $customer = $this->session->getCustomer();
                $customerData = $customer->setCompany_name(trim($login['company_name']));
                $customerData = $customer->setTax_code(trim($login['tax_code']));

                //Update Address + Region
                $params = $this->getRequest()->getPost();

                $firstname = $customer->getFirstname();
                $lastname = $customer->getLastname();

                $address = null;
                if($customer->getAddresses()){
                    foreach ($customer->getAddresses() as $k => $item){
                        $address = $item;
                        break;
                    }

                    $address->setFirstname($firstname)
                        ->setLastname($lastname)
                        ->setStreet($params['address'])
                        ->setTelephone('-')
                        ->setRegionId($params['region']);
                        
                }else{
                    $address = $this->addresss->create();

                    $address->setCustomerId($customer->getId())
                        ->setFirstname($firstname)
                        ->setLastname($lastname)
                        ->setCountryId('VN')
                        ->setPostcode(trim($login['tax_code']))
                        ->setRegionId($params['region'])
                        ->setTelephone('-')
                        ->setFax('-')
                        ->setCompany('-')
                        ->setStreet($params['address'])
                        ->setIsDefaultBilling('1')
                        ->setIsDefaultShipping('1')
                        ->setSaveInAddressBook('1');
                }

                $customer->save();

                $response = ['code' => 200, 'msg' => __('You have updated infomation successfully') ];
                $resultJson->setData($response); return $resultJson;
            }

            if (!empty($login['username']) && !empty($login['password'])) {
                $this->getCookiedata->delete('rememberme');
                
                try {
                    if (!empty($login['rememberme'])) {
                        $logindetails = array('email' => $login['username']);
                        $logindetails = json_encode($logindetails);
                        $this->getCookiedata->set($logindetails, $this->getCookiedata->getCookielifetime());
                    } else {
                        $this->getCookiedata->delete('rememberme');
                    }

                    //Validate account login
                    $customer = $this->customerAccountManagement->authenticate($login['username'], $login['password']);
                    $this->session->setCustomerDataAsLoggedIn($customer);
                    $this->session->regenerateId();
                    if ($this->getCookieManager()->getCookie('mage-cache-sessid')) {
                        $metadata = $this->getCookieMetadataFactory()->createCookieMetadata();
                        $metadata->setPath('/');
                        $this->getCookieManager()->deleteCookie('mage-cache-sessid', $metadata);
                    }

                } catch (EmailNotConfirmedException $e) {

                    $response = ['code' => 500, 'msg' => __('This account is not confirmed') ];
                    $resultJson->setData($response); return $resultJson;

                } catch (UserLockedException $e) {
                    $response = ['code' => 500, 'msg' => __('The account is locked.') ];
                    $resultJson->setData($response); return $resultJson;
                } catch (AuthenticationException $e) {
                    $response = ['code' => 500, 'msg' => __('Invalid login or password.') ];
                    $resultJson->setData($response); return $resultJson;
                } catch (\Exception $e) {
                    $response = ['code' => 500, 'msg' => __('An unspecified error occurred. Please contact us for assistance.') ];
                    $resultJson->setData($response); return $resultJson;
                }
            } else {

                $response = ['code' => 500, 'msg' => __('A login and a password are required.') ];
                $resultJson->setData($response); return $resultJson;

            }
        }

        //Return message to client
        $response = ['code' => 200, 'msg' => __('You have successfully login') ];
        $resultJson->setData($response); return $resultJson;
    }
}