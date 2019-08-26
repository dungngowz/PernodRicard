<?php
/**
 * DrinksAndCo_Agent extension
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category  DrinksAndCo
 * @package   DrinksAndCo_Agent
 * @copyright Copyright (c) 2019
 * @license   http://opensource.org/licenses/mit-license.php MIT License
 */
namespace DrinksAndCo\Agent\Model;

/**
 * @method \DrinksAndCo\Agent\Model\ResourceModel\Infomation _getResource()
 * @method \DrinksAndCo\Agent\Model\ResourceModel\Infomation getResource()
 */
class Infomation extends \Magento\Framework\Model\AbstractModel implements \DrinksAndCo\Agent\Api\Data\InfomationInterface
{
    /**
     * Cache tag
     * 
     * @var string
     */
    const CACHE_TAG = 'drinksandco_agent_infomation';

    /**
     * Cache tag
     * 
     * @var string
     */
    protected $_cacheTag = self::CACHE_TAG;

    /**
     * Event prefix
     * 
     * @var string
     */
    protected $_eventPrefix = 'drinksandco_agent_infomation';

    /**
     * Event object
     * 
     * @var string
     */
    protected $_eventObject = 'infomation';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\DrinksAndCo\Agent\Model\ResourceModel\Infomation::class);
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Get Infomation id
     *
     * @return array
     */
    public function getInfomationId()
    {
        return $this->getData(\DrinksAndCo\Agent\Api\Data\InfomationInterface::INFOMATION_ID);
    }

    /**
     * set Infomation id
     *
     * @param int $infomationId
     * @return \DrinksAndCo\Agent\Api\Data\InfomationInterface
     */
    public function setInfomationId($infomationId)
    {
        return $this->setData(\DrinksAndCo\Agent\Api\Data\InfomationInterface::INFOMATION_ID, $infomationId);
    }

    /**
     * set Name
     *
     * @param mixed $name
     * @return \DrinksAndCo\Agent\Api\Data\InfomationInterface
     */
    public function setName($name)
    {
        return $this->setData(\DrinksAndCo\Agent\Api\Data\InfomationInterface::NAME, $name);
    }

    /**
     * get Name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getData(\DrinksAndCo\Agent\Api\Data\InfomationInterface::NAME);
    }

    /**
     * set Email
     *
     * @param mixed $email
     * @return \DrinksAndCo\Agent\Api\Data\InfomationInterface
     */
    public function setEmail($email)
    {
        return $this->setData(\DrinksAndCo\Agent\Api\Data\InfomationInterface::EMAIL, $email);
    }

    /**
     * get Email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->getData(\DrinksAndCo\Agent\Api\Data\InfomationInterface::EMAIL);
    }

    /**
     * set Phone
     *
     * @param mixed $phone
     * @return \DrinksAndCo\Agent\Api\Data\InfomationInterface
     */
    public function setPhone($phone)
    {
        return $this->setData(\DrinksAndCo\Agent\Api\Data\InfomationInterface::PHONE, $phone);
    }

    /**
     * get Phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->getData(\DrinksAndCo\Agent\Api\Data\InfomationInterface::PHONE);
    }

    /**
     * set Region
     *
     * @param mixed $regionId
     * @return \DrinksAndCo\Agent\Api\Data\InfomationInterface
     */
    public function setRegionId($regionId)
    {
        return $this->setData(\DrinksAndCo\Agent\Api\Data\InfomationInterface::REGION_ID, $regionId);
    }

    /**
     * get Region
     *
     * @return string
     */
    public function getRegionId()
    {
        return $this->getData(\DrinksAndCo\Agent\Api\Data\InfomationInterface::REGION_ID);
    }

    /**
     * set Address
     *
     * @param mixed $address
     * @return \DrinksAndCo\Agent\Api\Data\InfomationInterface
     */
    public function setAddress($address)
    {
        return $this->setData(\DrinksAndCo\Agent\Api\Data\InfomationInterface::ADDRESS, $address);
    }

    /**
     * get Address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->getData(\DrinksAndCo\Agent\Api\Data\InfomationInterface::ADDRESS);
    }
}
