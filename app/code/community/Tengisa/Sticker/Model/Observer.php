<?php

class Tengisa_Sticker_Model_Observer {
    public function addStickerAttribute($observer) {
        $select = $observer->getSelect();
        $attributes = array('stickers');
        Mage::getResourceSingleton('tengisa_sticker/observer')
          ->addAttributesToSelect($select, $attributes);
    }
}
