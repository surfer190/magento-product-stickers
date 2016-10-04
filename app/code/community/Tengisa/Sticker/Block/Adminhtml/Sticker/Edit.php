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
 * Sticker admin edit form
 *
 * @category    Tengisa
 * @package     Tengisa_Sticker
 * @author      Ultimate Module Creator
 */
class Tengisa_Sticker_Block_Adminhtml_Sticker_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->_blockGroup = 'tengisa_sticker';
        $this->_controller = 'adminhtml_sticker';
        $this->_updateButton(
            'save',
            'label',
            Mage::helper('tengisa_sticker')->__('Save Sticker')
        );
        $this->_updateButton(
            'delete',
            'label',
            Mage::helper('tengisa_sticker')->__('Delete Sticker')
        );
        $this->_addButton(
            'saveandcontinue',
            array(
                'label'   => Mage::helper('tengisa_sticker')->__('Save And Continue Edit'),
                'onclick' => 'saveAndContinueEdit()',
                'class'   => 'save',
            ),
            -100
        );
        $this->_formScripts[] = "
            function saveAndContinueEdit() {
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    /**
     * get the edit form header
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getHeaderText()
    {
        if (Mage::registry('current_sticker') && Mage::registry('current_sticker')->getId()) {
            return Mage::helper('tengisa_sticker')->__(
                "Edit Sticker '%s'",
                $this->escapeHtml(Mage::registry('current_sticker')->getName())
            );
        } else {
            return Mage::helper('tengisa_sticker')->__('Add Sticker');
        }
    }
}
