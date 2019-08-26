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
namespace DrinksAndCo\Agent\Api;

/**
 * @api
 */
interface InfomationRepositoryInterface
{
    /**
     * Save Infomation.
     *
     * @param \DrinksAndCo\Agent\Api\Data\InfomationInterface $infomation
     * @return \DrinksAndCo\Agent\Api\Data\InfomationInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\DrinksAndCo\Agent\Api\Data\InfomationInterface $infomation);

    /**
     * Retrieve Infomation
     *
     * @param int $infomationId
     * @return \DrinksAndCo\Agent\Api\Data\InfomationInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($infomationId);

    /**
     * Retrieve Infomations matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \DrinksAndCo\Agent\Api\Data\InfomationSearchResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete Infomation.
     *
     * @param \DrinksAndCo\Agent\Api\Data\InfomationInterface $infomation
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\DrinksAndCo\Agent\Api\Data\InfomationInterface $infomation);

    /**
     * Delete Infomation by ID.
     *
     * @param int $infomationId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($infomationId);
}
