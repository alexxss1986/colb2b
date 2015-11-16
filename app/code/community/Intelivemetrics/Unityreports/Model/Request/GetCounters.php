<?php

/**
 * Get counters action
 *
 * @category  Unityreports
 * @package   Intelivemetrics_Unityreports
 * @copyright Copyright (c) 2014 Intelive Metrics Srl
 * @author    Eduard Gabriel Dumitrescu (balaur@gmail.com)
 */


class Intelivemetrics_Unityreports_Model_Request_GetCounters extends Intelivemetrics_Unityreports_Model_Request_Base implements Intelivemetrics_Unityreports_Model_Request_Interface {

    protected function _getCounters() {
        $helper = Mage::helper('unityreports');
        $counters = array(
            'orders'=> $helper->getTableCount('sales/order'),
            'invoices'=> $helper->getTableCount('sales/invoice'),
            'creditmemo'=> $helper->getTableCount('sales/creditmemo'),
            'customers'=> $helper->getTableCount('customer/entity'),
            'products'=> $helper->getTableCount('catalog/product'),
        );
        
        return $counters;
    }

    public function execute($settings=array()) {
        Intelivemetrics_Unityreports_Model_Utils::log(__METHOD__);

        $out = array(
            'done' => true,
            'info' => $this->_getCounters(),
        );

        //alles gut
        return $out;
    }

}

?>
