<?php

/**
 * Base class for all crons
 *
 * @category  Unityreports
 * @package   Intelivemetrics_Unityreports
 * @copyright Copyright (c) 2014 Intelive Metrics Srl
 * @author    Eduard Gabriel Dumitrescu (balaur@gmail.com)
 */


class Intelivemetrics_Unityreports_Model_Cron {

    /**
     * Gets a SOAP client
     * @return \Zend_Soap_Client
     */
    protected function _getClient() {
        return Mage::getModel('unityreports/utils')->getSoapClient();
    }

    public function _getDb() {
        return Mage::getSingleton('unityreports/utils')->getDb();
    }

    protected function _appIsOk($requireActiveSync = true) {
        return Mage::helper('unityreports')->appIsOk($requireActiveSync);
    }

}

?>
