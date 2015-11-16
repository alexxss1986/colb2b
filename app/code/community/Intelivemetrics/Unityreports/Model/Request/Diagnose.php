<?php

/**
 * Diagnose action
 *
 * @category  Unityreports
 * @package   Intelivemetrics_Unityreports
 * @copyright Copyright (c) 2014 Intelive Metrics Srl
 * @author    Eduard Gabriel Dumitrescu (balaur@gmail.com)
 */


class Intelivemetrics_Unityreports_Model_Request_Diagnose 
extends Intelivemetrics_Unityreports_Model_Request_Base 
implements Intelivemetrics_Unityreports_Model_Request_Interface {

    protected function _getCronsInfo() {
        $syncStat = Mage::getModel('unityreports/admin_status_cron_sync')->toHtml();
        $statStat = Mage::getModel('unityreports/admin_status_cron_stat')->toHtml();
        $countStat = Mage::getModel('unityreports/admin_status_cron_count')->toHtml();
        $globalStat = Mage::getModel('unityreports/admin_status_cron_globalCounters')->toHtml();
        $mageStat = Mage::getModel('unityreports/admin_status_cron_mage')->toHtml();
        
        $out="Sync: {$syncStat}
        Stat: {$statStat}
        Count: {$countStat}
        GlobalCounters: {$globalStat}
        Mage: {$mageStat}";

        return $out;
    }

    public function execute($settings=array()) {
        Intelivemetrics_Unityreports_Model_Utils::log(__METHOD__);
        
        $diagnosis ="CRONS:
            ".$this->_getCronsInfo();

        $out = array(
            'done' => true,
            'info' => $diagnosis,
        );

        //alles gut
        return $out;
    }

}

?>
