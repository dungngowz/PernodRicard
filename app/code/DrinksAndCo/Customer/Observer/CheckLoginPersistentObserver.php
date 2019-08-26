<?php
namespace DrinksAndCo\Customer\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

class CheckLoginPersistentObserver implements ObserverInterface
{
    /**
     * @var \Magento\Framework\App\Response\RedirectInterface
     */
    protected $redirect;

    /**
     * Customer session
     *
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;
    protected $_customCooike;
    protected $request;
    protected $_customerRepos;
    private $cookieMetadataManager;
    private $cookieMetadataFactory;
    protected $_commonHelper;

    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\App\Response\RedirectInterface $redirect,
        \Magento\Framework\App\Request\Http $request,
        \DrinksAndCo\Customer\Helper\Data $customCooike,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepos,
        \DrinksAndCo\Base\Helper\Common $commonHelper
    ) {

        $this->_customerSession = $customerSession;
        $this->redirect = $redirect;
        $this->request = $request;
        $this->_customerRepos = $customerRepos;
        $this->_customCooike = $customCooike;
        $this->_commonHelper = $commonHelper;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $controller = $observer->getControllerAction();
        $module = $this->request->getModuleName();

        if ($module == 'dccustomer' || $module == 'customer') {
            return $this; //if in allowed actions do nothing.
        }


        if(!$this->_customerSession->isLoggedIn()) {
            $cookieData = $this->_customCooike->get('rememberme');
            if($cookieData){
                $cookieData = json_decode($cookieData);

                $customer = $this->_customerRepos->get($cookieData->email);

                $this->_customerSession->setCustomerDataAsLoggedIn($customer);
                $this->_customerSession->regenerateId();
                if ($this->getCookieManager()->getCookie('mage-cache-sessid')) {
                    $metadata = $this->getCookieMetadataFactory()->createCookieMetadata();
                    $metadata->setPath('/');
                    $this->getCookieManager()->deleteCookie('mage-cache-sessid', $metadata);
                }
            }else{
                $this->redirect->redirect($controller->getResponse(), 'wellcome');
            }
        }else{
            //Check b2b first login
            $isB2B = $this->_commonHelper->isB2B();
            $customer = $this->_customerSession->getCustomer();

            if($isB2B && ( empty($customer->getCompany_name()) || empty($customer->getTax_code()) ) ){
                $this->redirect->redirect($controller->getResponse(), 'dccustomer/auth/b2bfirstlogin');
            }
        }
    }

    private function getCookieManager()
    {
        if (!$this->cookieMetadataManager) {
            $this->cookieMetadataManager = \Magento\Framework\App\ObjectManager::getInstance()->get(
                \Magento\Framework\Stdlib\Cookie\PhpCookieManager::class
            );
        }
        return $this->cookieMetadataManager;
    }

    private function getCookieMetadataFactory()
    {
        if (!$this->cookieMetadataFactory) {
            $this->cookieMetadataFactory = \Magento\Framework\App\ObjectManager::getInstance()->get(
                \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory::class
            );
        }
        return $this->cookieMetadataFactory;
    }
}
