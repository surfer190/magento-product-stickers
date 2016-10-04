<?php
/**
 * Tengisa_Sticker extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Tengisa
 * @package        Tengisa_Sticker
 * @copyright      Copyright (c) 2016
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Sticker resource model
 *
 * @category    Tengisa
 * @package     Tengisa_Sticker
 * @author      Ultimate Module Creator
 */
class Tengisa_Sticker_Model_Resource_Sticker extends Mage_Core_Model_Resource_Db_Abstract
{

    /**
     * constructor
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function _construct()
    {
        $this->_init('tengisa_sticker/sticker', 'entity_id');
    }

    /**
     * Get store ids to which specified item is assigned
     *
     * @access public
     * @param int $stickerId
     * @return array
     * @author Ultimate Module Creator
     */
    public function lookupStoreIds($stickerId)
    {
        $adapter = $this->_getReadAdapter();
        $select  = $adapter->select()
            ->from($this->getTable('tengisa_sticker/sticker_store'), 'store_id')
            ->where('sticker_id = ?', (int)$stickerId);
        return $adapter->fetchCol($select);
    }

    /**
     * Perform operations after object load
     *
     * @access public
     * @param Mage_Core_Model_Abstract $object
     * @return Tengisa_Sticker_Model_Resource_Sticker
     * @author Ultimate Module Creator
     */
    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        if ($object->getId()) {
            $stores = $this->lookupStoreIds($object->getId());
            $object->setData('store_id', $stores);
        }
        return parent::_afterLoad($object);
    }

    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @param Tengisa_Sticker_Model_Sticker $object
     * @return Zend_Db_Select
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);
        if ($object->getStoreId()) {
            $storeIds = array(Mage_Core_Model_App::ADMIN_STORE_ID, (int)$object->getStoreId());
            $select->join(
                array('sticker_sticker_store' => $this->getTable('tengisa_sticker/sticker_store')),
                $this->getMainTable() . '.entity_id = sticker_sticker_store.sticker_id',
                array()
            )
            ->where('sticker_sticker_store.store_id IN (?)', $storeIds)
            ->order('sticker_sticker_store.store_id DESC')
            ->limit(1);
        }
        return $select;
    }

    /**
     * Assign sticker to store views
     *
     * @access protected
     * @param Mage_Core_Model_Abstract $object
     * @return Tengisa_Sticker_Model_Resource_Sticker
     * @author Ultimate Module Creator
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        $oldStores = $this->lookupStoreIds($object->getId());
        $newStores = (array)$object->getStores();
        if (empty($newStores)) {
            $newStores = (array)$object->getStoreId();
        }
        $table  = $this->getTable('tengisa_sticker/sticker_store');
        $insert = array_diff($newStores, $oldStores);
        $delete = array_diff($oldStores, $newStores);
        if ($delete) {
            $where = array(
                'sticker_id = ?' => (int) $object->getId(),
                'store_id IN (?)' => $delete
            );
            $this->_getWriteAdapter()->delete($table, $where);
        }
        if ($insert) {
            $data = array();
            foreach ($insert as $storeId) {
                $data[] = array(
                    'sticker_id'  => (int) $object->getId(),
                    'store_id' => (int) $storeId
                );
            }
            $this->_getWriteAdapter()->insertMultiple($table, $data);
        }
        return parent::_afterSave($object);
    }
}
