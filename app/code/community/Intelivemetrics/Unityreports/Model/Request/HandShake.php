<?php

/**
 * Handshake action
 *
 * @category  Unityreports
 * @package   Intelivemetrics_Unityreports
 * @copyright Copyright (c) 2014 Intelive Metrics Srl
 * @author    Eduard Gabriel Dumitrescu (balaur@gmail.com)
 */


class Intelivemetrics_Unityreports_Model_Request_HandShake extends Intelivemetrics_Unityreports_Model_Request_Base implements Intelivemetrics_Unityreports_Model_Request_Interface {

    protected function _getSystemInfo() {
        //php info
        ob_start();
        phpinfo();
        $phpinfo = ob_get_clean();
        $phpinfo = strip_tags($phpinfo);

        return $phpinfo;
    }

    protected function _getMageInfo() {
        $db =Mage::getSingleton('unityreports/utils')->getDb();
        $result = $db->query("SELECT * FROM core_resource");

        if (!$result) {
            $db->closeConnection();
            return FALSE;
        }

        $modules='';
        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            $modules.="{$row['code']} - {$row['version']} - {$row['data_version']}\n";
        }

        $db->closeConnection();
        return $modules;
        
    }
    
    protected function _getGA() {
        //TODO: handle GA on store view level
        $ga = Mage::getStoreConfig('google/analytics/account');
        return $ga;
    }

    public function execute($settings=array()) {
        Intelivemetrics_Unityreports_Model_Utils::log(__METHOD__);


        $out = array(
            'done' => true,
            'info' => array(
                'phpinfo' => $this->_getSystemInfo(),
                'mageinfo' => $this->_getMageInfo(),
                'ga' => $this->_getGA(),
            )
        );

        //alles gut
        return $out;
    }

}

?>
