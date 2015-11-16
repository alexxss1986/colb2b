<?php

/**
 * Description of Createshipment
 *
 * @author Roberto
 */
class Webgriffe_Tntpro_Block_Adminhtml_Newshipment_Createinvoice extends Mage_Adminhtml_Block_Sales_Order_Invoice_Create_Form {

    protected function _toHtml() {
         $html = parent::_toHtml();
         //aggiungo il blocck di tnt
         $torep = '<div id="tracking" style="display:none;">';
         $html = preg_replace("/".str_replace('/',"\\/",$torep)."/i", $torep.$this->getLayout()->getBlock('tntproform')->toHtml(), $html, 1);
         return $html;
    }

}

