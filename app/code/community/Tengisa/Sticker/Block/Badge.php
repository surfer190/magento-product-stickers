<?php

class Tengisa_Sticker_Block_Badge extends Mage_Core_Block_Template
{

    const POSITION = array('1' => 'top-left',
                            '2' => 'top-right',
                            '3' => 'bottom-left',
                            '4' => 'bottom-right');

    public function getBadgeContent($id){
        //check if it is a badge
        $sticker    = Mage::getModel('tengisa_sticker/sticker');
        if ($id) {
            $sticker->load($id);
            if ($sticker->getType() == '1'){
              $position = Tengisa_Sticker_Block_Badge::POSITION[$sticker->getData('position')];
              return "<div class='tengisa-sticker-badge $position'>" . $sticker->getConent() . "</div>";
            }
        }
        return '';
    }

    public function getPanelContent($id){
        //check if it is a panel
        $sticker    = Mage::getModel('tengisa_sticker/sticker');
        if ($id) {
            $sticker->load($id);
            if ($sticker->getType() == '2'){
              return "<div class='tengisa-sticker-panel'>" . $sticker->getConent() . "</div>";
            }
        }
        return '';
    }
}
