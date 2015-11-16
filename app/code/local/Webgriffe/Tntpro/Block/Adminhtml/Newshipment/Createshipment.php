<?php

/**
 * Description of Createshipment
 *
 * @author Roberto
 */
class Webgriffe_Tntpro_Block_Adminhtml_Newshipment_Createshipment extends Mage_Adminhtml_Block_Sales_Order_Shipment_Create_Form {

    protected function _toHtml() {
         $html = parent::_toHtml();
         //aggiungo il blocck di tnt
         $torep = '<div class="grid">';
         $html = preg_replace("/".str_replace('/',"\\/",$torep)."/i", '<br />'.$this->getLayout()->getBlock('tntproform')->toHtml().$torep, $html, 1);
         return $html;
    }

}

