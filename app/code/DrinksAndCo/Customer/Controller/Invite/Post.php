<?php
namespace DrinksAndCo\Customer\Controller\Invite;
 
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\AddressFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Escaper;
use Magento\Framework\UrlFactory;
use Magento\Customer\Model\Session;
use Magento\Customer\Model\Registration;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Controller\Result\JsonFactory;
use DrinksAndCo\Invite\Model\FriendFactory;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Math\Random;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\Framework\App\Config\ScopeConfigInterface;
use DrinksAndCo\Base\Helper\Common as CommoneHelper;
use DrinksAndCo\Customer\Helper\Email;

class Post extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;
 
    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $customerFactory;
 
    /**
     * @var \Magento\Customer\Model\AddressFactory
     */
    protected $addressFactory;
 
    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;
 
    /**
     * @var \Magento\Framework\Escaper
     */
    protected $escaper;
 
    /**
     * @var \Magento\Framework\UrlFactory
     */
    protected $urlFactory;
 
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $session;
 
    /**
     * @var Magento\Framework\Data\Form\FormKey\Validator
     */
    private $formKeyValidator;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var \DrinksAndCo\Invite\Model\FriendFactory
     */
    protected $friendFactory;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress
     */
    protected $remoteAddress;

     /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Framework\Math\Random
     */
    protected $mathRandom;

    protected $helperEmail;
 
    /**
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param CustomerFactory $customerFactory
     * @param AddressFactory $addressFactory
     * @param ManagerInterface $messageManager
     * @param Escaper $escaper
     * @param UrlFactory $urlFactory
     * @param Session $session
     * @param Validator $formKeyValidator
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        CustomerFactory $customerFactory,
        AddressFactory $addressFactory,
        ManagerInterface $messageManager,
        Escaper $escaper,
        UrlFactory $urlFactory,
        Session $session,
        Validator $formKeyValidator = null,
        JsonFactory $resultJsonFactory,
        FriendFactory $friendFactory,
        DateTime $dateTime,
        Random $mathRandom,
        RemoteAddress $remoteAddress,
        ScopeConfigInterface $scopeConfig,
        CommoneHelper $commonHelper,
        Email $helperEmail
    ){
        $this->storeManager     = $storeManager;
        $this->customerFactory  = $customerFactory;
        $this->addressFactory   = $addressFactory;
        $this->messageManager   = $messageManager;
        $this->escaper          = $escaper;
        $this->urlModel         = $urlFactory->create();
        $this->session          = $session;
        $this->formKeyValidator = $formKeyValidator ?: ObjectManager::getInstance()->get(Validator::class);
        
        $this->resultJsonFactory = $resultJsonFactory;
        $this->friendFactory = $friendFactory;
        $this->dateTime = $dateTime;
        $this->mathRandom = $mathRandom;
        $this->remoteAddress = $remoteAddress;
        $this->scopeConfig = $scopeConfig;

        $this->commonHelper = $commonHelper;

        $this->helperEmail = $helperEmail;
 
        parent::__construct($context);
    }
 
    /**
     * Default customer account page
     *
     * @return void
     */
    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();

        $customerCurrent = $this->session->getCustomer();

        //Check can invitation friend and family
        if(!$this->commonHelper->canInviteFriends()){
            $response = ['code' => 'max-invite', 'msg' => 'Your request is over the limitation' ];
            $resultJson->setData($response); return $resultJson;
        }

        $resultRedirect = $this->resultRedirectFactory->create();
 
        //Get Website Id
        $websiteId  = $this->storeManager->getWebsite()->getWebsiteId();
 
        //Set Params
        $phone = $this->getRequest()->getPost('telephone');
        $region = $this->getRequest()->getPost('region');
        $email = $this->getRequest()->getPost('email');
        list($firstName) = explode("@", $email);
        $lastName = ' ';
        $password = $phone;
 
        // instantiate customer object
        $customer = $this->customerFactory->create();
        $customer->setWebsiteId($websiteId);
        
        // check if customer is already present
        // if customer is already present, then show error message
        // else create new customer
        if ($customer->loadByEmail($email)->getId()) {
            $message = __( 'There is already an account with this email address "%1".', $email );
            
            $response = ['code' => 500, 'msg' => $message ];
            $resultJson->setData($response); return $resultJson;

        } else {
            try {
                // prepare customer data
                $groupId = $this->commonHelper->setGroupInvite();

                $customer->setGroupId($groupId); //Friend And Family
                $customer->setEmail($email); 
                $customer->setFirstname($firstName);
                $customer->setLastname($lastName);
 
                // set null to auto-generate password
                $customer->setPassword($password); 
 
                // set the customer as confirmed
                // this is optional
                // comment out this line if you want to send confirmation email
                // to customer before finalizing his/her account creation
                $customer->setForceConfirmed(false);
                
                // save data
                $customer->save();
                
                // save customer address
                // this is optional
                // you can skip saving customer address while creating the customer
                $customerAddress = $this->addressFactory->create();

                $customerAddress->setCustomerId($customer->getId())
                                ->setFirstname($firstName)
                                ->setLastname($lastName)
                                ->setCountryId('VN')
                                ->setRegionId($region) // optional, depends upon Country, e.g. USA
                                ->setRegion('-') // optional, depends upon Country, e.g. USA
                                ->setPostcode('-')
                                ->setCity('-')
                                ->setTelephone($phone)
                                ->setStreet(array('0' => '-', '1' => '-')) 
                                ->setIsDefaultBilling('1')
                                ->setIsDefaultShipping('1')
                                ->setSaveInAddressBook('1');
                
                try {
                    // save customer address
                    $customerAddress->save();
                } catch (Exception $e) {
                    $response = ['code' => 500, 'msg' => __('We can\'t save the customer address.') ];
                    $resultJson->setData($response); return $resultJson;
                }  

                //Import data to table drinksandco_invite_friend
                $inviteFriendModel = $this->friendFactory->create();
                $inviteFriendModel->setData([
                    'customer_id_staff' => $this->session->getCustomerId(),
                    'customer_id_relative' => $customer->getId(),
                    'create_at' => $this->dateTime->gmtDate(),
                    'token' => $this->mathRandom->getUniqueHash(),
                    'ip' => $this->remoteAddress->getRemoteAddress()
                ]);

                try {
                    $inviteFriendModel->save();
                } catch (Exception $e) {
                    $response = ['code' => 500, 'msg' => __($this->escaper->escapeHtml($e->getMessage())) ];
                    $resultJson->setData($response); return $resultJson;
                }
 
                // send welcome email to the customer
                $paramsEmail = [
                    'email_invite' => $customerCurrent->getEmail(),
                    'email_receipt' => $email,
                    'password' => $password,
                    'url' => 'https://prvn.codingart.co/en/wellcome#token=' . rand(1000,9999) . $password . '-' . $email,
                    'template' => 'invite_friend_and_family'
                ];
                $this->helperEmail->sendEmail($paramsEmail);


                $message = __( 'Customer account with email %1 created successfully.', $email );

                $againInviteFriends = $this->commonHelper->againInviteFriends();

                $response = ['code' => 'success-invite', 'msg' => $message, 'againInviteFriends' => $againInviteFriends ];
                $resultJson->setData($response); return $resultJson;

            } catch (StateException $e) {
                $response = ['code' => 200, 'msg' => __('There is already an account with this email address') ];
                $resultJson->setData($response); return $resultJson;

            } catch (LocalizedException $e) {
                $response = ['code' => 200, 'msg' => __($this->escaper->escapeHtml($e->getMessage())) ];
                $resultJson->setData($response); return $resultJson;

            } catch (\Exception $e) {
                $response = ['code' => 200, 'msg' => __($this->escaper->escapeHtml($e->getMessage())) ];
                $resultJson->setData($response); return $resultJson;
            }
        }
 
        //Return message to client
        $response = ['code' => 'success-invite', 'msg' => __('You have successfully sent an invitation') ];
        $resultJson->setData($response); return $resultJson;
    }
}