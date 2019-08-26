<?php
namespace DrinksAndCo\Base\Helper;

class Common extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $_pageFactory;
	protected $_categoryCollectionFactory;
    protected $_categoryHelper;
    protected $_categoryFactory;

    /**
     * @var \DrinksAndCo\Invite\Model\FriendFactory
     */
    protected $friendFactory;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $session;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;

    /**
     * @var \Magento\CatalogRule\Model\ResourceModel\Rule
     */
    protected $resourceRule;

    /**
     * Customer Group
     *
     * @var \Magento\Customer\Model\ResourceModel\Group\Collection
     */
    protected $_customerGroup;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;
    protected $price;
    protected $datetime;

    protected $filter;
    protected $orderRepository;
    protected $orderModel;
    protected $cartModel;
    protected $catalogrule;
    protected $_store;
    protected $_sessionManager;
    protected $_visitor;
    protected $timezone;

    /**
     *
     * @var \Magento\Catalog\Helper\Image
     */
    protected $_productImageHelper;

    public $idRootProduct = 21;
    public $idRootExp = 17;

	public function __construct(
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
		\Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Catalog\Helper\Category $categoryHelper,
        \DrinksAndCo\Invite\Model\FriendFactory $friendFactory,
        \Magento\Customer\Model\Session $session,
        \Magento\Store\Api\Data\StoreInterface $store,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\CatalogRule\Model\ResourceModel\Rule $resourceRule,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Product\Type\Price $price,
        \Magento\Framework\Stdlib\DateTime\DateTime $datetime,
        \Magento\Customer\Model\ResourceModel\Group\Collection $customerGroup,
        \Magento\Framework\Filter\FilterManager $filter,
        \Magento\Sales\Model\OrderRepository $orderRepository,
        \Magento\Sales\Model\Order $orderModel,
        \Magento\Checkout\Model\Cart $cartModel,
        \Magento\CatalogRule\Model\Rule $catalogrule,
        \Magento\Customer\Model\Visitor $visitor,
        \Magento\Framework\Session\SessionManagerInterface $sessionManager,
        \Magento\Catalog\Helper\Image $productImageHelper,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
	){
		$this->_categoryCollectionFactory = $categoryCollectionFactory;
        $this->_categoryHelper = $categoryHelper;
        $this->_categoryFactory = $categoryFactory;
        $this->friendFactory = $friendFactory;
        $this->session = $session;
        $this->_sessionManager = $sessionManager;
        $this->scopeConfig = $scopeConfig;
        $this->orderModel = $orderModel;
        $this->_productFactory = $productFactory;
        $this->resourceRule = $resourceRule;
        $this->storeManager = $storeManager;
        $this->datetime = $datetime;
        $this->price = $price;
        $this->_store = $store;
        $this->_customerGroup = $customerGroup;
        $this->filter = $filter;
        $this->catalogrule = $catalogrule;
        $this->orderRepository = $orderRepository;
        $this->cartModel = $cartModel;
        $this->_visitor = $visitor;
        $this->_productImageHelper = $productImageHelper;
        $this->timezone = $timezone;
    }

    public function limitText($text = '', $limit = 120){
        $text = strip_tags(trim($text));
        if (str_word_count($text, 0) > $limit) {
            $words = str_word_count($text, 2);

            $pos = array_keys($words);
            $text = substr($text, 0, $pos[$limit]) . '...';
        }
        return $text;
    }

    public function getLocaleCode(){
        return $this->_store->getLocaleCode();
    }

    public function getChildCategoryExperience(){
        return $this->loadCategoryById($this->idRootExp)->getChildrenCategories();
    }

    public function getRecentProduct($catUrlKey = 'experiences', $pageSize = 5){
        $id = ($catUrlKey == 'experiences') ? $this->idRootExp : $this->idRootProduct;

        $category = $this->loadCategoryById($id);
        $collection = $category->getProductCollection()->addAttributeToSelect('*');

        //Get product by customer group
        if($catUrlKey == 'products'){
            $currentCustomerGroup = $this->getCustomerGroupCatalog();
            $collection->addAttributeToFilter('customer_groups', ['finset' => $currentCustomerGroup]);
        }

        $collection->setPageSize($pageSize)->setOrder('created_at', 'desc');
        return $collection;
    }

    public function getRelatedProduct($productId, $catUrlKey = 'experiences', $pageSize = 12){
        $idRootCategory = ($catUrlKey == 'experiences') ? $this->idRootExp : $this->idRootProduct;
        $category = $this->loadCategoryById($idRootCategory);

        $detailProduct = $this->getDetailProductById($productId);

        $collection = $category->getProductCollection()->addAttributeToSelect('*');

        //Get product by customer group
        if($catUrlKey == 'products'){
            $currentCustomerGroup = $this->getCustomerGroupCatalog();
            $collection->addAttributeToFilter('customer_groups', ['finset' => $currentCustomerGroup]);

            $categoryCollection = $detailProduct->getCategoryCollection()
                ->addAttributeToFilter('dc_new_attribute', 'level1')
                ->toArray();
            if(count($categoryCollection) > 0){
                $categoryIds = [];
                foreach($categoryCollection as $item){
                    $categoryIds[] = $item['entity_id'];
                }
            }else{
                $categoryIds = [$this->idRootProduct];
            }
        }else{
            $categoryIds = [$this->idRootExp];
        }

        $collection->addAttributeToFilter('entity_id', array('neq' => $productId))
            ->addCategoriesFilter(array('in' => $categoryIds))
            ->setOrder('created_at', 'desc')
            ->setPageSize($pageSize);

        return ($collection->count() > 0) ? $collection : false;
    }

    /**
     * Get customer groups
     * 
     * @return array
     */ 
    public function getCustomerGroups() {
        $customerGroups = $this->_customerGroup->toOptionArray();
        array_unshift($customerGroups, array('value'=>'', 'label'=>'Any'));
        return $customerGroups;
    }

    public function getPriceProductByGroupCustomer(){
        return $this->resourceRule->getRulePrice(new \DateTime(), 1, 4, 5);
    }

    public function setGroupInvite(){
        $group = $this->session->getCustomer()->getGroupId();
        return $group == 1 ? 2 : 4;
    }

    /**
     * @param $productId
     * @param $customerGroupId
     * @return mixed
     */
    public function getCatalogPriceRuleFromProduct($productId, $customerGroupId = false)
    {
        $customerGroupId = $customerGroupId ? $customerGroupId : $this->session->getCustomer()->getGroupId();

        $websiteId  = $this->storeManager->getWebsite()->getWebsiteId();
        $product = $this->_productFactory->create()->load($productId);
        $dateTs = $this->datetime->gmtDate();
        $rules = $this->resourceRule->getRulesFromProduct($dateTs, $websiteId, $customerGroupId, $productId);

        return $product->getPrice();
    }

    public function setTitlePage($pageConfig, $title = 'Custom Title'){
        $pageConfig->getTitle()->set(__($title) . ' - ' . $pageConfig->getTitle()->getDefault());
    }

    public function loadCategoryByUrlKey($urlKey){
        return $this->_categoryFactory->create()->loadByAttribute('url_key', $urlKey);
    }

    public function isRootCategoryProduct($ids){
        $ids = is_array($ids) ? $ids : [$ids];
        return in_array(21, $ids) ? true : false;
    }

    public function isRootCategoryExp($ids){
        $ids = is_array($ids) ? $ids : [$ids];
        return in_array(17, $ids) ? true : false;
    }

    public function getRootCategory($currentCategory){
		if($currentCategory->getParentCategories()){
			foreach ($currentCategory->getParentCategories() as $parent) {
				if ($parent->getLevel() == 2) {
					return $parent->getId();
				}
			}
		}
        return null;
    }

    public function getDetailRootCategoryExp(){
        return $this->_categoryFactory->create()->load($this->idRootExp);
    }

    public function loadCategoryById($id){
        return $this->_categoryFactory->create()->load($id);
    }

    public function countInvitations(){
        return $this->friendFactory->create()
            ->getCollection()
            ->addFieldToFilter( 'customer_id_staff', $this->session->getId())
            ->count();
    }

    public function canInviteFriends(){
        $maxInvite = $this->getMaxInvite();
        $countFriends = $this->countInvitations();

        return ( $maxInvite - $countFriends > 0 ) ? true : false;
    }

    public function againInviteFriends(){
        $maxInvite = $this->getMaxInvite();
        $countFriends = $this->countInvitations();

        return $maxInvite - $countFriends;
    }

    public function getMaxInvite(){
        $customerGroup = $this->session->getCustomer()->getGroupId();

        if($customerGroup == 1){
            $codeGroup = 'line_manager';
        }else if($customerGroup == 2){
            $codeGroup = 'b2b';
        }else if($customerGroup == 3){
            $codeGroup = 'staff';
        }else{
            $codeGroup = 'friend_and_family';
        }

        return $this->scopeConfig->getValue('customer_invite/general_settings/' . $codeGroup, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getCustomerGroupCatalog(){
        $id = $this->session->getCustomer()->getGroupId();
        return $id + 28;
    }

    public function getCurrentCustomer(){
        if ( $this->session->isLoggedIn()){
            return $this->session->getCustomer();
        }
        return false;
    }

    public function getRegionOfCurrentCustomer(){
        $currentCustomer = $this->getCurrentCustomer();
        if($currentCustomer){
            $address = null;
            if($currentCustomer->getAddresses()){
                foreach ($currentCustomer->getAddresses() as $k => $item){
                    $address = $item;
                    break;
                }
            }

            if($address){
                return $address->getRegionCode();
            }
        }
        return null;
    }

    public function getAreaByRegionId($regionCode){
        $top = [
            'LAOCAI', 'YENBAI', 'DIENBIEN', 'HOABINH', 'LAICHAU', 'SONLA', 'HAGIANG', 'CAOBANG',
            'BACKAN', 'LANGSON', 'TUYENQUANG', 'THAINGUYEN', 'PHUTHO', 'BACGIANG', 'QUANGNINH',
            'BACNINH', 'HANAM', 'TPHANOI', 'HAIDUONG', 'HUNGYEN', 'TPHAIPHONG', 'NAMDINH', 'NINHBINH', 'THAIBINH', 'VINHPHUC'
        ];

        if(in_array($regionCode, $top)){
            return 1;
        }

        $mid = [
            'THANHHOA', 'NGHEAN', 'HATINH', 'QUANGBINH', 'QUANGTRI', 'THUATHIENHUE', 'DANANG', 'QUANGNAM', 'QUANGNGAI',
            'BINHDINH', 'PHUYEN', 'KHANHHOA', 'NINHTHUAN', 'BINHTHUAN',
            'KONTUM', 'GIALAI', 'DAKLAK', 'DAKNONG', 'LAMDONG'
        ];

        if(in_array($regionCode, $mid)){
            return 2;
        }

        return 3;

        // $bottom = [
        //     'BINHPHUOC', 'BINHDUONG', 'DONGNAI', 'TAYNINH', 'BARIAVUNGTAU', 'TPHOCHIMINH', 'LONGAN', 'DONGTHAP', 'TIENGIANG', 'ANGIANG',
        //     'BENTRE', 'VINHLONG', 'TRAVINH', 'HAUGIANG', 'KIENGIANG', 'SOCTRANG', 'BACLIEU', 'CAMAU', 'TPCANTHO'
        // ];

    }

    public function getEmailSaleManager(){
        $regionCode = $this->getRegionOfCurrentCustomer();
        $area = $this->getAreaByRegionId($regionCode) ; // bắc:1, trung: 2, nam: 3 

        return $this->scopeConfig->getValue('customer_invite/email_settings/email_system_' . $area, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getPriceLimit(){
        $customerGroup = $this->session->getCustomer()->getGroupId();

        if($customerGroup == 1){
            $codeGroup = 'line_manager';
        }else if($customerGroup == 2){
            $codeGroup = 'b2b';
        }else if($customerGroup == 3){
            $codeGroup = 'staff';
        }else{
            $codeGroup = 'friend_and_family';
        }
        return $this->scopeConfig->getValue('customer_invite/price_settings/' . $codeGroup, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getConfigValue($pathConfig){
        return $this->scopeConfig->getValue($pathConfig, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);;
    }

    public function getDetailProduct($sku){
        return $this->_productFactory->create()->loadByAttribute('sku', $sku);
    }

    public function getDetailProductById($id){
        return $this->_productFactory->create()->load($id);
    }

    public function getDetailCategory($sku){
        return $this->_categoryFactory->create()->loadByAttribute('url_key', $sku);
    }

    public function redirectToHomePage($url = '/'){
        header("Location: " . $url);
	    exit();
    }

    public function getUrlDetailProduct($urlKey){
        return $this->storeManager->getStore()->getBaseUrl() . __('detail-product') . '/' . $urlKey;
    }

    public function getUrlDetailOrder($orderId){
        return $this->storeManager->getStore()->getUrl('admin/sales/order/view', ['order_id' => $orderId]);
    }

    public function getOrderById($id) {
        return $this->orderRepository->get($id);
    }

    public function getCurrentCustomerId(){
        return $this->session->getCustomer()->getId();
    }

    public function getCurrentCustomerGroupId(){
        return $this->session->getCustomer()->getGroupId();
    }

    public function isB2B(){
        $groupCustomer = $this->getCurrentCustomerGroupId();
        return ($groupCustomer == 2) ? true : false;
    }

    public function getDiscountPriceProduct($product, $group){
        $discountedPrice = $this->catalogrule->calcProductPriceRule(
            $product->setStoreId(1)->setCustomerGroupId($group),
            $product->getPrice()
        );

        return $discountedPrice;
    }

    public function allowedOrder($totalCart = 0){
        $customerId = $this->getCurrentCustomerId();
        $customerGroupId = $this->getCurrentCustomerGroupId();

        $startDate = date('Y') . '-' . date('m') . '-01';
        $endDate = date('Y') . '-' . date('m') . '-31';
        
        $orderTotals = $this->orderModel->getCollection()
            ->addAttributeToFilter('customer_id', $customerId)
            ->addAttributeToSelect('grand_total')
            ->addAttributeToFilter('created_at', array('from'=>$startDate, 'to'=>$endDate))
            ->addFieldToFilter('status',
                ['nin' => ['canceled']]
            )
            ->getColumnValues('grand_total');

        $orderTotals = $orderTotals ? array_sum($orderTotals) : 0;

        // get quote items array
        $max = $this->getPriceLimit();
        $total = $orderTotals + intval($totalCart);

        return ($total >= $max) ? false : true;
    }

    public function isFirstLogin(){
        $visitors = $this->_visitor->getCollection()->addFieldToFilter('customer_id', array('eq' => 3))->count();
        $cookie_name = 'isFirstLogin';

        if($visitors > 1 || (isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == $cookie_name)){
            return false;
        }

        setcookie($cookie_name, $cookie_name, time()+7*3600);
        return true;
    }

    /**
     * resize of the image
     * @see \Magento\Catalog\Model\Product\Image
     * @param int $width
     * @param int $height
     * @return $this
     */
    public function resizeImageProduct($id, $image_type, $width = 200, $height = null)
    {
        $product = $this->getDetailProductById($id);
        if(!$product){
            return null;
        }
        $resizedImage = $this->_productImageHelper->init($product, $image_type)
                            ->constrainOnly(TRUE)
                            ->keepAspectRatio(TRUE)
                            ->resize($width);

        return $resizedImage->getUrl();
    }

    public function getCategoryBrands($cateParent){
        $category = $this->loadCategoryById($cateParent);
        $idCats = explode(',', $category->getChildren(true));

        $collection = $this->_categoryCollectionFactory->create();

        $collection->addAttributeToFilter('entity_id', array('in' => array($idCats)));
        $collection->addAttributeToFilter('dc_new_attribute', 'level1');
        $collection->addAttributeToSelect('*');

        return $collection;
    }

    public function getDeliveryDate($orderCreatedAt = false){

        if(empty($orderCreatedAt)){
            $date = new \DateTime(null);
            $date = $date->format('Y-m-d');
        }

        $espDay = 0;
        $dayOfWeek = date('N', strtotime($date));
        
        if($dayOfWeek == 1){//Thu 2
            $espDay = 4;
        }else if($dayOfWeek == 2){//Thu 3
            $espDay = 3;
        }else if($dayOfWeek == 3){//Thu 4
            $espDay = 9;
        }else if($dayOfWeek == 4){//Thu 5
            $espDay = 8;
        }else if($dayOfWeek == 5){//Thu 6
            $espDay = 7;
        }else if($dayOfWeek == 6){//Thu 7
            $espDay = 6;
        }else if($dayOfWeek == 7){//Chu nhat
            $espDay = 5;
        }
        
        return date('Y-m-d', strtotime($date. ' + '.$espDay.' days'));
    }
}