<?php

/**
 * Description of Newshipment
 *
 * @author Roberto
 */
class Webgriffe_Tntpro_Block_Adminhtml_Newshipment extends Mage_Adminhtml_Block_Template {

    public function getOrder() {
        $ship = $this->getShipment();
        if ($ship != null) {
            return $ship->getOrder();
        } else {
            return $this->getInvoice()->getOrder();
        }
    }

    public function getInvoice() {
        return Mage::registry('current_invoice');    
    }
    
    public function getShipment() {
        return Mage::registry('current_shipment');
    }

    protected function _getAllowSymlinks() {
        return TRUE;
    }

    public function fetchView($fileName) {
        $this->setScriptPath(
                Mage::getModuleDir('', 'Webgriffe_Tntpro') . DS . 'templates'
        );

        return parent::fetchView($this->getTemplate());
    }

    public function inDebug() {
        return Mage::helper('wgtntpro')->inDebug();
    }

    public function getChecked(){
        return Mage::helper('wgtntpro')->isDefaultEnabled() ? ' checked="checked" ':'';
    }
    
    public function virgola($str) { #usato nella vecchia versione che voleva i prezzi con la virgola. nella pro usiamo il .
        return number_format($str,2,'.',''); #str_replace('.' , ',' , $str);
    }
}