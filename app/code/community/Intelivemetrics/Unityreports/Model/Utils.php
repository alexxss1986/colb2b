<?php

/**
 * Utilities
 *
 * @category  Unityreports
 * @package   Intelivemetrics_Unityreports
 * @copyright Copyright (c) 2014 Intelive Metrics Srl
 * @author    Eduard Gabriel Dumitrescu (balaur@gmail.com)
 */


class Intelivemetrics_Unityreports_Model_Utils {
    
    protected static $_db = null;
    protected static $_soapClient = null;

    public static function getTableName($table) {
        return Mage::getSingleton('core/resource')->getTableName($table);
    }

    public static function log($msg) {
        $helper = Mage::helper('unityreports');
        $helper->debug($msg);
    }

    public static function getLicenseKey() {
        $helper = Mage::helper('unityreports');
        return $helper->getLicenseKey();
    }

    /**
     * Make data safe for db writing
     * @param type $string
     * @return type
     */
    public static function sanitize($string) {
        $string = strip_tags($string);
        $string = addslashes($string);

        return $string;
    }

    /**
     * Get module config
     * @param type $key
     * @return string|array
     */
    public static function getConfig($key = null) {
        $db = Mage::getModel('core/resource')->getConnection('core_read');
        $table = self::getTableName('unityreports/settings');
        $key = self::sanitize($key);
        
        if (is_null($key)) {
            $result = $db->query("SELECT `val` FROM `{$table}`");
            $out = array();
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $out[$row['key']] = $row['val'];
            }
            return $out;
        } else {
            if (!$result = $db->query("SELECT `val` FROM `{$table}` WHERE `key`='{$key}'")) {
                self::log("No settings for key $key");
                return '';
            }
            $row = $result->fetch(PDO::FETCH_ASSOC);
            return $row['val'];
        }
        $db->closeConnection();
    }

    /**
     * Set module config key
     * @param type $key
     * @param type $val
     * @return type
     */
    public static function setConfig($key, $val) {
        $db = Mage::getModel('core/resource')->getConnection('core_read');
        $table = self::getTableName('unityreports/settings');
        $key = self::sanitize($key);
        $val = self::sanitize($val);
        
        try {
            $db->query("REPLACE INTO `{$table}`(`key`,`val`) VALUES('{$key}','{$val}')");
            self::log("set $key to $val");
            return true;
        } catch (Exception $ex) {
            self::log($ex->getMessage());
            return false;
        }
        $db->closeConnection();
    }
    
    /**
     * Gets a SOAP client
     * @return \Zend_Soap_Client
     */
    public static function getSoapClient() {
        $ws_endpoint = Mage::getStoreConfig('unityreports/general/ws_endpoint');

        // inizializza client SOAP
        if(is_null(self::$_soapClient)){
            self::$_soapClient = new Zend_Soap_Client($ws_endpoint . "?wsdl");
            self::$_soapClient->setWsdlCache(1);
        }

        return self::$_soapClient;
    }
    
    /**
     * Get db write connection
     * @return type
     */
    public static function getDb(){
        if(is_null(self::$_db)){
            self::$_db = Mage::getModel('core/resource')->getConnection('core_write');
        }
        
        return self::$_db;
    }
    
    /**
     * How many items of each type to send in one sync session
     * @return int
     */
    public static function getMaxItemsPerSync(){
        $items = Intelivemetrics_Unityreports_Model_Utils::getConfig('max_items_per_sync');
        if(!$items || is_null($items)){
            $items = Intelivemetrics_Unityreports_Model_Config::MAX_ITEMS_PER_SYNC;
        }
        
        return $items;
    }
    
    public static function prepareDataForSending($data){
        return base64_encode(gzcompress(serialize($data)));
    }

}

?>
