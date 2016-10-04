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
 * Sticker admin edit tabs
 *
 * @category    Tengisa
 * @package     Tengisa_Sticker
 * @author      Ultimate Module Creator
 */
class Tengisa_Sticker_Block_Adminhtml_Sticker_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Initialize Tabs
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('sticker_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('tengisa_sticker')->__('Sticker'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return Tengisa_Sticker_Block_Adminhtml_Sticker_Edit_Tabs
     * @author Ultimate Module Creator
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_sticker',
            array(
                'label'   => Mage::helper('tengisa_sticker')->__('Sticker'),
                'title'   => Mage::helper('tengisa_sticker')->__('Sticker'),
                'content' => $this->getLayout()->createBlock(
                    'tengisa_sticker/adminhtml_sticker_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        if (!Mage::app()->isSingleStoreMode()) {
            $this->addTab(
                'form_store_sticker',
                array(
                    'label'   => Mage::helper('tengisa_sticker')->__('Store views'),
                    'title'   => Mage::helper('tengisa_sticker')->__('Store views'),
                    'content' => $this->getLayout()->createBlock(
                        'tengisa_sticker/adminhtml_sticker_edit_tab_stores'
                    )
                    ->toHtml(),
                )
            );
        }
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve sticker entity
     *
     * @access public
     * @return Tengisa_Sticker_Model_Sticker
     * @author Ultimate Module Creator
     */
    public function getSticker()
    {
        return Mage::registry('current_sticker');
    }
}
