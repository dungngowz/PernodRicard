<?php
/**
 * Umc_Base extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 *
 * @category  Umc
 * @package   Umc_Base
 * @copyright Marius Strajeru
 * @license   http://opensource.org/licenses/mit-license.php MIT License
 * @author    Marius Strajeru <ultimate.module.creator@gmail.com>
 */
namespace Umc\Base\Config\Form\Mapper;

use Umc\Base\Config\Mapper\Sorting as AbstractSorting;

class Sorting extends AbstractSorting
{
    /**
     * map data
     *
     * @param array $data
     * @return array
     */
    public function map(array $data)
    {
        if (isset($data['forms']['form']) && is_array($data['forms']['form'])) {
            foreach ($data['forms']['form'] as &$element) {
                $element = $this->processConfig($element);
            }
        }
        return $data;
    }

    /**
     * process config data
     *
     * @param array $data
     * @return mixed
     */
    protected function processConfig($data)
    {
        if (isset($data['fieldset'])) {
            foreach ($data['fieldset'] as $fieldset => $fields) {
                $data['fieldset'][$fieldset] = $this->processConfig($fields);
            }
            uasort($data['fieldset'], [$this, 'cmp']);
        }
        if (isset($data['field'])) {
            uasort($data['field'], [$this, 'cmp']);
        }
        return $data;
    }
}
