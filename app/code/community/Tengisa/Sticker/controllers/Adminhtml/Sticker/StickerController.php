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
 * Sticker admin controller
 *
 * @category    Tengisa
 * @package     Tengisa_Sticker
 * @author      Ultimate Module Creator
 */
class Tengisa_Sticker_Adminhtml_Sticker_StickerController extends Tengisa_Sticker_Controller_Adminhtml_Sticker
{
    /**
     * init the sticker
     *
     * @access protected
     * @return Tengisa_Sticker_Model_Sticker
     */
    protected function _initSticker()
    {
        $stickerId  = (int) $this->getRequest()->getParam('id');
        $sticker    = Mage::getModel('tengisa_sticker/sticker');
        if ($stickerId) {
            $sticker->load($stickerId);
        }
        Mage::register('current_sticker', $sticker);
        return $sticker;
    }

    /**
     * default action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->_title(Mage::helper('tengisa_sticker')->__('Manage Stickers'))
             ->_title(Mage::helper('tengisa_sticker')->__('Stickers'));
        $this->renderLayout();
    }

    /**
     * grid action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function gridAction()
    {
        $this->loadLayout()->renderLayout();
    }

    /**
     * edit sticker - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function editAction()
    {
        $stickerId    = $this->getRequest()->getParam('id');
        $sticker      = $this->_initSticker();
        if ($stickerId && !$sticker->getId()) {
            $this->_getSession()->addError(
                Mage::helper('tengisa_sticker')->__('This sticker no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getStickerData(true);
        if (!empty($data)) {
            $sticker->setData($data);
        }
        Mage::register('sticker_data', $sticker);
        $this->loadLayout();
        $this->_title(Mage::helper('tengisa_sticker')->__('Manage Stickers'))
             ->_title(Mage::helper('tengisa_sticker')->__('Stickers'));
        if ($sticker->getId()) {
            $this->_title($sticker->getName());
        } else {
            $this->_title(Mage::helper('tengisa_sticker')->__('Add sticker'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new sticker action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * save sticker - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('sticker')) {
            try {
                $sticker = $this->_initSticker();
                $sticker->addData($data);
                $sticker->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('tengisa_sticker')->__('Sticker was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $sticker->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setStickerData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('tengisa_sticker')->__('There was a problem saving the sticker.')
                );
                Mage::getSingleton('adminhtml/session')->setStickerData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('tengisa_sticker')->__('Unable to find sticker to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete sticker - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $sticker = Mage::getModel('tengisa_sticker/sticker');
                $sticker->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('tengisa_sticker')->__('Sticker was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('tengisa_sticker')->__('There was an error deleting sticker.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('tengisa_sticker')->__('Could not find sticker to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete sticker - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massDeleteAction()
    {
        $stickerIds = $this->getRequest()->getParam('sticker');
        if (!is_array($stickerIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('tengisa_sticker')->__('Please select stickers to delete.')
            );
        } else {
            try {
                foreach ($stickerIds as $stickerId) {
                    $sticker = Mage::getModel('tengisa_sticker/sticker');
                    $sticker->setId($stickerId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('tengisa_sticker')->__('Total of %d stickers were successfully deleted.', count($stickerIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('tengisa_sticker')->__('There was an error deleting stickers.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass status change - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massStatusAction()
    {
        $stickerIds = $this->getRequest()->getParam('sticker');
        if (!is_array($stickerIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('tengisa_sticker')->__('Please select stickers.')
            );
        } else {
            try {
                foreach ($stickerIds as $stickerId) {
                $sticker = Mage::getSingleton('tengisa_sticker/sticker')->load($stickerId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d stickers were successfully updated.', count($stickerIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('tengisa_sticker')->__('There was an error updating stickers.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Type change - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massTypeAction()
    {
        $stickerIds = $this->getRequest()->getParam('sticker');
        if (!is_array($stickerIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('tengisa_sticker')->__('Please select stickers.')
            );
        } else {
            try {
                foreach ($stickerIds as $stickerId) {
                $sticker = Mage::getSingleton('tengisa_sticker/sticker')->load($stickerId)
                    ->setType($this->getRequest()->getParam('flag_type'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d stickers were successfully updated.', count($stickerIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('tengisa_sticker')->__('There was an error updating stickers.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Position change - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massPositionAction()
    {
        $stickerIds = $this->getRequest()->getParam('sticker');
        if (!is_array($stickerIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('tengisa_sticker')->__('Please select stickers.')
            );
        } else {
            try {
                foreach ($stickerIds as $stickerId) {
                $sticker = Mage::getSingleton('tengisa_sticker/sticker')->load($stickerId)
                    ->setPosition($this->getRequest()->getParam('flag_position'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d stickers were successfully updated.', count($stickerIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('tengisa_sticker')->__('There was an error updating stickers.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * export as csv - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportCsvAction()
    {
        $fileName   = 'sticker.csv';
        $content    = $this->getLayout()->createBlock('tengisa_sticker/adminhtml_sticker_grid')
            ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as MsExcel - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportExcelAction()
    {
        $fileName   = 'sticker.xls';
        $content    = $this->getLayout()->createBlock('tengisa_sticker/adminhtml_sticker_grid')
            ->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as xml - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportXmlAction()
    {
        $fileName   = 'sticker.xml';
        $content    = $this->getLayout()->createBlock('tengisa_sticker/adminhtml_sticker_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Check if admin has permissions to visit related pages
     *
     * @access protected
     * @return boolean
     * @author Ultimate Module Creator
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('cms/tengisa_sticker/sticker');
    }
}
