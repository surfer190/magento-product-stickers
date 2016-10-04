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
 * Sticker model
 *
 * @category    Tengisa
 * @package     Tengisa_Sticker
 * @author      Ultimate Module Creator
 */
class Tengisa_Sticker_Model_Sticker extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'tengisa_sticker_sticker';
    const CACHE_TAG = 'tengisa_sticker_sticker';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'tengisa_sticker_sticker';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'sticker';

    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('tengisa_sticker/sticker');
    }

    /**
     * before save sticker
     *
     * @access protected
     * @return Tengisa_Sticker_Model_Sticker
     * @author Ultimate Module Creator
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()) {
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);
        return $this;
    }

    /**
     * get the sticker Content
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getConent()
    {
        $conent = $this->getData('conent');
        $helper = Mage::helper('cms');
        $processor = $helper->getBlockTemplateProcessor();
        $html = $processor->filter($conent);
        return $html;
    }

    /**
     * save sticker relation
     *
     * @access public
     * @return Tengisa_Sticker_Model_Sticker
     * @author Ultimate Module Creator
     */
    protected function _afterSave()
    {
        return parent::_afterSave();
    }

    /**
     * get default values
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getDefaultValues()
    {
        $values = array();
        $values['status'] = 1;
        $values['type'] = '1';

        return $values;
    }
    
}
