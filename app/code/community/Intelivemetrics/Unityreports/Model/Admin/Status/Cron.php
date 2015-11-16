<?php

/**
 * Base class for cron checks
 *
 * @category  Unityreports
 * @package   Intelivemetrics_Unityreports
 * @copyright Copyright (c) 2014 Intelive Metrics Srl
 * @author    Eduard Gabriel Dumitrescu (balaur@gmail.com)
 */

class Intelivemetrics_Unityreports_Model_Admin_Status_Cron extends Mage_Core_Model_Abstract {
    /**
     * Checks if sync cron is active
     * Returns false for errors, array (status:0|1,executed_at:datetime) otherwise
     * @return boolean|array
     */
    public function getStatus() {
        $helper = Mage::helper('unityreports');
        $db = Mage::getSingleton('unityreports/utils')->getDb();
        $table = Mage::getSingleton('unityreports/utils')->getTableName('cron/schedule');
        $result = $db->query($query = "SELECT executed_at FROM {$table} 
                WHERE job_code='{$this->_jobName}' AND executed_at IS NOT NULL
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
    
    public function toHtml() {
        $status = self::getStatus();
        if (is_array($status)) {
            if ($status['status'] == 0) {
                return 'Inactive';
            } elseif ($status['status'] == 1) {
                return 'Active - last executed at:' . $status['executed_at'];
            }
        }

        return 'Undefined';
    }

    public function toOptionArray() {
        return array();
    }
}
