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
 * Sticker edit form tab
 *
 * @category    Tengisa
 * @package     Tengisa_Sticker
 * @author      Ultimate Module Creator
 */
class Tengisa_Sticker_Block_Adminhtml_Sticker_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return Tengisa_Sticker_Block_Adminhtml_Sticker_Edit_Tab_Form
     * @author Ultimate Module Creator
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('sticker_');
        $form->setFieldNameSuffix('sticker');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'sticker_form',
            array('legend' => Mage::helper('tengisa_sticker')->__('Sticker'))
        );
        $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig();

        $fieldset->addField(
            'name',
            'text',
            array(
                'label' => Mage::helper('tengisa_sticker')->__('Name'),
                'name'  => 'name',
                'required'  => true,
                'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'type',
            'select',
            array(
                'label' => Mage::helper('tengisa_sticker')->__('Type'),
                'name'  => 'type',
                'required'  => true,
                'class' => 'required-entry',

                'values'=> Mage::getModel('tengisa_sticker/sticker_attribute_source_type')->getAllOptions(true),
           )
        );

        $fieldset->addField(
            'conent',
            'editor',
            array(
                'label' => Mage::helper('tengisa_sticker')->__('Content'),
                'name'  => 'conent',
            'config' => $wysiwygConfig,

           )
        );

        $fieldset->addField(
            'position',
            'select',
            array(
                'label' => Mage::helper('tengisa_sticker')->__('Position'),
                'name'  => 'position',
                'required'  => true,
                'class' => 'required-entry',

                'values'=> Mage::getModel('tengisa_sticker/sticker_attribute_source_position')->getAllOptions(true),
           )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('tengisa_sticker')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('tengisa_sticker')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('tengisa_sticker')->__('Disabled'),
                    ),
                ),
            )
        );
        if (Mage::app()->isSingleStoreMode()) {
            $fieldset->addField(
                'store_id',
                'hidden',
                array(
                    'name'      => 'stores[]',
                    'value'     => Mage::app()->getStore(true)->getId()
                )
            );
            Mage::registry('current_sticker')->setStoreId(Mage::app()->getStore(true)->getId());
        }
        $formValues = Mage::registry('current_sticker')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getStickerData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getStickerData());
            Mage::getSingleton('adminhtml/session')->setStickerData(null);
        } elseif (Mage::registry('current_sticker')) {
            $formValues = array_merge($formValues, Mage::registry('current_sticker')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
