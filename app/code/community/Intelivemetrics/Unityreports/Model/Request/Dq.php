<?php

/**
 * Direct queryaction
 *
 * @category  Unityreports
 * @package   Intelivemetrics_Unityreports
 * @copyright Copyright (c) 2014 Intelive Metrics Srl
 * @author    Eduard Gabriel Dumitrescu (balaur@gmail.com)
 */
class Intelivemetrics_Unityreports_Model_Request_Dq extends Intelivemetrics_Unityreports_Model_Request_Base implements Intelivemetrics_Unityreports_Model_Request_Interface {

    public function execute($params) {
        $q = stripslashes(strip_tags($params[0]));
        if (!$q) {
            die('Missing Q Param');
        }
        $_q = $q;
        $names = array('abcarts', 'creditnotes', 'customers', 'customer_actions', 'invoices', 'orders', 'products', 'campaigns', 'settings', 'product_counters');
        foreach ($names as $name) {
            $_q = str_replace('{' . $name . '}', Intelivemetrics_Unityreports_Model_Utils::getTableName('unityreports/' . $name), $_q);
        }
        if (md5($q) == md5($_q)) {
            die('Something\'s wrong');
        }
        
        $helper = Mage::helper('unityreports');
        try {
            $helper->debug("Executing DG: $_q");
            $r = Mage::getSingleton('core/resource')
                    ->getConnection('core_read')
                    ->fetchAll($_q);
            $result = "Affected rows: " . count($r) . '
                '. print_r($r, true);
        } catch (Exception $ex) {
            $result = 'Error: '.$ex->getMessage();
        }
        $helper->debug($result);

        return $out = array(
            'done' => true,
            'info' => $result
        );
    }

}

?>
