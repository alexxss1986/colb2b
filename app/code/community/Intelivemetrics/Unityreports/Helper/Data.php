<?php

/**
 * Helper class
 *
 * @category  Unityreports
 * @package   Intelivemetrics_Unityreports
 * @copyright Copyright (c) 2014 Intelive Metrics Srl
 * @author    Eduard Gabriel Dumitrescu (balaur@gmail.com)
 */
class Intelivemetrics_Unityreports_Helper_Data extends Mage_Core_Helper_Abstract {

    protected $_loggers = null;

    /**
     * Check module is correctly setup and sync is activated
     * @return boolean
     */
    public function appIsOk($requireActiveSync = true) {
        //check license has been setup
        if (!$this->getLicenseKey()) {
            $this->debug('License Key hasn\'t been setup.');
            return false;
        }
        //is sync active?
        if ($requireActiveSync) {
            $onoff = Mage::getStoreConfig(Intelivemetrics_Unityreports_Model_Config::XML_GENERAL_STATUS);
            if ($onoff === '0') {
                $this->debug('Sync is deactivated');
                return false;
            }
        }

        return true;
    }

    public function getLogger() {
        $file = Intelivemetrics_Unityreports_Model_Config::LOGFILE;

        if (!is_null($this->_loggers[$file]))
            return $this->_loggers[$file];

        try {
            $logDir = Mage::getBaseDir('var') . DS . 'log';
            $logFile = $logDir . DS . $file;

            if (!is_dir($logDir)) {
                mkdir($logDir);
                chmod($logDir, 0777);
            }

            if (!file_exists($logFile)) {
                file_put_contents($logFile, '');
                chmod($logFile, 0777);
            }

            $format = '%timestamp% %priorityName% (%priority%): %message%' . PHP_EOL;
            $formatter = new Zend_Log_Formatter_Simple($format);
            $writerModel = (string) Mage::getConfig()->getNode('global/log/core/writer_model');
            if (!Mage::app() || !$writerModel) {
                $writer = new Zend_Log_Writer_Stream($logFile);
            } else {
                $writer = new $writerModel($logFile);
            }
            $writer->setFormatter($formatter);
            $this->_loggers[$file] = new Zend_Log($writer);

            return $this->_loggers[$file];
        } catch (Exception $ex) {
            echo $ex->getTraceAsString();
        }
    }

    /**
     * logga il messaggio nel file di log
     * 
     * @param string $mesg messaggio da loggare
     */
    public function debug($message, $level = Zend_Log::INFO) {
        if (!$this->isDebug() || empty($message)) {
            return;
        }

        if (is_array($message) || is_object($message)) {
            $message = print_r($message, true);
        }

        if (defined('APPLICATION_ENV') && APPLICATION_ENV == 'testing') {
            //if on testing, don't log errors throw exception
            if ($level < Zend_Log::WARN)
                throw new Exception($message);
            else
                print('NOTICE(' . $level . '): ' . $message . "\n");
        }else {
            //if not on testing don't log debug msg 
            if ($level > Zend_Log::INFO) {
                return;
            }
        }

        try {
            $logger = $this->getLogger();
            $logger->log($message, $level);
        } catch (Exception $e) {
            echo $e->getTraceAsString();
        }
    }

    /**
     * Returns true if module is on debug (debug select is ON)
     * @return bool 
     */
    public function isDebug() {
        $testing = (defined('APPLICATION_ENV') && APPLICATION_ENV == 'testing' ? true : false);
        $debug = (Mage::getStoreConfig('unityreports/advanced/debug_status') > 0 ? true : false);
        return ($debug || $testing);
    }

    /**
     * Returns true if syncing is active (status select is ON)
     * @return bool
     */
    public function isActive() {
        return (Mage::getStoreConfig(Intelivemetrics_Unityreports_Model_Config::XML_GENERAL_STATUS) === '1');
    }

    public function getLicenseKey() {
        return Mage::getStoreConfig('unityreports/general/license_serial_number');
    }

    public function getApiKey() {
        return Mage::getStoreConfig('unityreports/general/api_key');
    }

    public function getApiSecret() {
        return Mage::getStoreConfig('unityreports/general/api_secret');
    }

    public function getEndpointUrl() {
        return Mage::getStoreConfig('unityreports/general/ws_endpoint');
    }

    /**
     * Ritorna il BASE_URL
     * 
     * @return type
     */
    public function getBaseUrl() {
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
    }

    /**
     * Ritorna il numero totale di clienti
     * 
     * @return type
     */
    public function getCustomerCount() {
        return $this->getTableCount('customer_entity');
    }

    /**
     * Ritorna il numero totale di prodotti
     * 
     * @return type
     */
    public function getProductCount() {
        return $this->getTableCount('catalog_product_entity');
    }

    /**
     * Ritorna il numero totale di ordini
     * 
     * @return type
     */
    public function getSalesOrderCount() {
        return $this->getTableCount('sales_flat_order');
    }

    /**
     * Ritorna il numero totale di fatture
     * 
     * @return type
     */
    public function getSalesInvoiceCount() {
        return $this->getTableCount('sales_flat_invoice');
    }

    /**
     * Ritorna il numero totale di categorie
     * 
     * @return type
     */
    public function getCategoryCount() {
        return $this->getTableCount('catalog_category_entity');
    }

    /**
     * Ritorna il numero di record nella tabella
     * 
     * @param type $tableName
     * @param array $condition = array('key'=>'active','val'=>'1')
     * @return type
     */
    public function getTableCount($tableName, $condition = array()) {
        $table = Mage::getSingleton('core/resource')->getTableName($tableName);
        $sql = "select count(*) ncount from $table";
        if ((isset($condition['key']) && !empty($condition['key'])) && (isset($condition['val']) && !empty($condition['val']))) {
            $sql .= " WHERE {$condition['key']} = {$condition['val']}";
        }
        $connection = Mage::getSingleton('core/resource')->getConnection('core_read');
        $rescount = $connection->fetchRow($sql);
        $ncount = $rescount['ncount'];
        return $ncount;
    }

}

?>