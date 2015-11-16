<?php

/**
 * Checks status of magento's cron
 *
 * @category  Unityreports
 * @package   Intelivemetrics_Unityreports
 * @copyright Copyright (c) 2014 Intelive Metrics Srl
 * @author    Eduard Gabriel Dumitrescu (balaur@gmail.com)
 */

class Intelivemetrics_Unityreports_Model_Admin_Status_Cron_Mage extends Intelivemetrics_Unityreports_Model_Admin_Status_Cron {
    /**
     * Checks if sync cron is active
     * Returns false for errors, array (status:0|1,executed_at:datetime) otherwise
     * @return boolean|array
     */
    public function getStatus() {
        $helper = Mage::helper('unityreports');
        $db = Mage::getModel('core/resource')->getConnection('core_write');
        $table = Mage::getModel('unityreports/utils')->getTableName('cron/schedule');
        $result = $db->query($query = "SELECT executed_at FROM {$table} 
                WHERE DATE(executed_at)='" . date('Y-m-d') . "' 
                ORDER BY executed_at DESC 
                LIMIT 0,1");

        if (!$result) {
            $helper->debug("Cannot query: {$query}");
            $db->closeConnection();
            return FALSE;
        }

        $row = $result->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            $db->closeConnection();
            return array('status' => 0);
        }

        $executed_at=$row['executed_at'];
        $db->closeConnection();
        return array('status' => 1, 'executed_at' => $executed_at);
    }


}

?>
