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
namespace DrinksAndCo\Agent\Api\Data;

/**
 * @api
 */
interface InfomationInterface
{
    /**
     * ID
     * 
     * @var string
     */
    const INFOMATION_ID = 'infomation_id';

    /**
     * Name attribute constant
     * 
     * @var string
     */
    const NAME = 'name';

    /**
     * Email attribute constant
     * 
     * @var string
     */
    const EMAIL = 'email';

    /**
     * Phone attribute constant
     * 
     * @var string
     */
    const PHONE = 'phone';

    /**
     * Region attribute constant
     * 
     * @var string
     */
    const REGION_ID = 'region_id';

    /**
     * Address attribute constant
     * 
     * @var string
     */
    const ADDRESS = 'address';

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getInfomationId();

    /**
     * Set ID
     *
     * @param int $infomationId
     * @return InfomationInterface
     */
    public function setInfomationId($infomationId);

    /**
     * Get Name
     *
     * @return mixed
     */
    public function getName();

    /**
     * Set Name
     *
     * @param mixed $name
     * @return InfomationInterface
     */
    public function setName($name);

    /**
     * Get Email
     *
     * @return mixed
     */
    public function getEmail();

    /**
     * Set Email
     *
     * @param mixed $email
     * @return InfomationInterface
     */
    public function setEmail($email);

    /**
     * Get Phone
     *
     * @return mixed
     */
    public function getPhone();

    /**
     * Set Phone
     *
     * @param mixed $phone
     * @return InfomationInterface
     */
    public function setPhone($phone);

    /**
     * Get Region
     *
     * @return mixed
     */
    public function getRegionId();

    /**
     * Set Region
     *
     * @param mixed $regionId
     * @return InfomationInterface
     */
    public function setRegionId($regionId);

    /**
     * Get Address
     *
     * @return mixed
     */
    public function getAddress();

    /**
     * Set Address
     *
     * @param mixed $address
     * @return InfomationInterface
     */
    public function setAddress($address);
}
