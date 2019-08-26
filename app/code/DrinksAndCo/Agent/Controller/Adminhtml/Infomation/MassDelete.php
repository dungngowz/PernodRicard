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
namespace DrinksAndCo\Agent\Controller\Adminhtml\Infomation;

class MassDelete extends \DrinksAndCo\Agent\Controller\Adminhtml\Infomation\MassAction
{
    /**
     * @param \DrinksAndCo\Agent\Api\Data\InfomationInterface $infomation
     * @return $this
     */
    protected function _massAction(\DrinksAndCo\Agent\Api\Data\InfomationInterface $infomation)
    {
        $this->_infomationRepository->delete($infomation);
        return $this;
    }
}
