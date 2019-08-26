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
namespace DrinksAndCo\Agent\Model\ResourceModel;

class Infomation extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Event Manager
     * 
     * @var \Magento\Framework\Event\ManagerInterface
     */
    protected $_eventManager;

    /**
     * constructor
     * 
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param mixed $connectionName
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        $connectionName = null
    ) {
        $this->_eventManager = $eventManager;
        parent::__construct($context, $connectionName);
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('drinksandco_agent_infomation', 'infomation_id');
    }

    /**
     * Retrieves Infomation Name from DB by passed id.
     *
     * @param string $id
     * @return string|bool
     */
    public function getInfomationNameById($id)
    {
        $adapter = $this->getConnection();
        $select = $adapter->select()
            ->from($this->getMainTable(), 'name')
            ->where('infomation_id = :infomation_id');
        $binds = ['infomation_id' => (int)$id];
        return $adapter->fetchOne($select, $binds);
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     * @param array $attribute
     * @return $this
     * @throws \Exception
     */
    public function saveAttribute(\Magento\Framework\Model\AbstractModel $object, $attribute)
    {
        if (is_string($attribute)) {
            $attributes = [$attribute];
        } else {
            $attributes = $attribute;
        }
        if (is_array($attributes) && !empty($attributes)) {
            $this->getConnection()->beginTransaction();
            $data = array_intersect_key($object->getData(), array_flip($attributes));
            try {
                $this->_beforeSaveAttribute($object, $attributes);
                if ($object->getId() && !empty($data)) {
                    $this->getConnection()->update(
                        $object->getResource()->getMainTable(),
                        $data,
                        [$object->getResource()->getIdFieldName() . '= ?' => (int)$object->getId()]
                    );
                    $object->addData($data);
                }
                $this->_afterSaveAttribute($object, $attributes);
                $this->getConnection()->commit();
            } catch (\Exception $e) {
                $this->getConnection()->rollBack();
                throw $e;
            }
        }
        return $this;
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     * @param array $attribute
     * @return $this
     */
    protected function _beforeSaveAttribute(\Magento\Framework\Model\AbstractModel $object, $attribute)
    {
        if ($object->getEventObject() && $object->getEventPrefix()) {
            $this->_eventManager->dispatch(
                $object->getEventPrefix() . '_save_attribute_before',
                [
                    $object->getEventObject() => $this,
                    'object' => $object,
                    'attribute' => $attribute
                ]
            );
        }
        return $this;
    }

    /**
     * After save object attribute
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @param string $attribute
     * @return $this
     */
    protected function _afterSaveAttribute(\Magento\Framework\Model\AbstractModel $object, $attribute)
    {
        if ($object->getEventObject() && $object->getEventPrefix()) {
            $this->_eventManager->dispatch(
                $object->getEventPrefix() . '_save_attribute_after',
                [
                    $object->getEventObject() => $this,
                    'object' => $object,
                    'attribute' => $attribute
                ]
            );
        }
        return $this;
    }

    /**
     * before save callback
     *
     * @param \Magento\Framework\Model\AbstractModel|\DrinksAndCo\Agent\Model\Infomation $object
     * @return $this
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        return parent::_beforeSave($object);
    }
}
