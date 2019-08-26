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
interface InfomationSearchResultInterface
{
    /**
     * Get Infomations list.
     *
     * @return \DrinksAndCo\Agent\Api\Data\InfomationInterface[]
     */
    public function getItems();

    /**
     * Set Infomations list.
     *
     * @param \DrinksAndCo\Agent\Api\Data\InfomationInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
