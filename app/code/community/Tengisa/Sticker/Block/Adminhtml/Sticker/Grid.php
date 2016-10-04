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
 * Sticker admin grid block
 *
 * @category    Tengisa
 * @package     Tengisa_Sticker
 * @author      Ultimate Module Creator
 */
class Tengisa_Sticker_Block_Adminhtml_Sticker_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * constructor
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('stickerGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return Tengisa_Sticker_Block_Adminhtml_Sticker_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('tengisa_sticker/sticker')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return Tengisa_Sticker_Block_Adminhtml_Sticker_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('tengisa_sticker')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'name',
            array(
                'header'    => Mage::helper('tengisa_sticker')->__('Name'),
                'align'     => 'left',
                'index'     => 'name',
            )
        );
        
        $this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('tengisa_sticker')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('tengisa_sticker')->__('Enabled'),
                    '0' => Mage::helper('tengisa_sticker')->__('Disabled'),
                )
            )
        );
        $this->addColumn(
            'type',
            array(
                'header' => Mage::helper('tengisa_sticker')->__('Type'),
                'index'  => 'type',
                'type'  => 'options',
                'options' => Mage::helper('tengisa_sticker')->convertOptions(
                    Mage::getModel('tengisa_sticker/sticker_attribute_source_type')->getAllOptions(false)
                )

            )
        );
        $this->addColumn(
            'position',
            array(
                'header' => Mage::helper('tengisa_sticker')->__('Position'),
                'index'  => 'position',
                'type'  => 'options',
                'options' => Mage::helper('tengisa_sticker')->convertOptions(
                    Mage::getModel('tengisa_sticker/sticker_attribute_source_position')->getAllOptions(false)
                )

            )
        );
        if (!Mage::app()->isSingleStoreMode() && !$this->_isExport) {
            $this->addColumn(
                'store_id',
                array(
                    'header'     => Mage::helper('tengisa_sticker')->__('Store Views'),
                    'index'      => 'store_id',
                    'type'       => 'store',
                    'store_all'  => true,
                    'store_view' => true,
                    'sortable'   => false,
                    'filter_condition_callback'=> array($this, '_filterStoreCondition'),
                )
            );
        }
        $this->addColumn(
            'created_at',
            array(
                'header' => Mage::helper('tengisa_sticker')->__('Created at'),
                'index'  => 'created_at',
                'width'  => '120px',
                'type'   => 'datetime',
            )
        );
        $this->addColumn(
            'updated_at',
            array(
                'header'    => Mage::helper('tengisa_sticker')->__('Updated at'),
                'index'     => 'updated_at',
                'width'     => '120px',
                'type'      => 'datetime',
            )
        );
        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('tengisa_sticker')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('tengisa_sticker')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('tengisa_sticker')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('tengisa_sticker')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('tengisa_sticker')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return Tengisa_Sticker_Block_Adminhtml_Sticker_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('sticker');
        $this->getMassactionBlock()->addItem(
            'delete',
            array(
                'label'=> Mage::helper('tengisa_sticker')->__('Delete'),
                'url'  => $this->getUrl('*/*/massDelete'),
                'confirm'  => Mage::helper('tengisa_sticker')->__('Are you sure?')
            )
        );
        $this->getMassactionBlock()->addItem(
            'status',
            array(
                'label'      => Mage::helper('tengisa_sticker')->__('Change status'),
                'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                'additional' => array(
                    'status' => array(
                        'name'   => 'status',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('tengisa_sticker')->__('Status'),
                        'values' => array(
                            '1' => Mage::helper('tengisa_sticker')->__('Enabled'),
                            '0' => Mage::helper('tengisa_sticker')->__('Disabled'),
                        )
                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'type',
            array(
                'label'      => Mage::helper('tengisa_sticker')->__('Change Type'),
                'url'        => $this->getUrl('*/*/massType', array('_current'=>true)),
                'additional' => array(
                    'flag_type' => array(
                        'name'   => 'flag_type',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('tengisa_sticker')->__('Type'),
                        'values' => Mage::getModel('tengisa_sticker/sticker_attribute_source_type')
                            ->getAllOptions(true),

                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'position',
            array(
                'label'      => Mage::helper('tengisa_sticker')->__('Change Position'),
                'url'        => $this->getUrl('*/*/massPosition', array('_current'=>true)),
                'additional' => array(
                    'flag_position' => array(
                        'name'   => 'flag_position',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('tengisa_sticker')->__('Position'),
                        'values' => Mage::getModel('tengisa_sticker/sticker_attribute_source_position')
                            ->getAllOptions(true),

                    )
                )
            )
        );
        return $this;
    }

    /**
     * get the row url
     *
     * @access public
     * @param Tengisa_Sticker_Model_Sticker
     * @return string
     * @author Ultimate Module Creator
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    /**
     * get the grid url
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

    /**
     * after collection load
     *
     * @access protected
     * @return Tengisa_Sticker_Block_Adminhtml_Sticker_Grid
     * @author Ultimate Module Creator
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    /**
     * filter store column
     *
     * @access protected
     * @param Tengisa_Sticker_Model_Resource_Sticker_Collection $collection
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     * @return Tengisa_Sticker_Block_Adminhtml_Sticker_Grid
     * @author Ultimate Module Creator
     */
    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $collection->addStoreFilter($value);
        return $this;
    }
}
