<?php

/**
 * Base class for all action requests
 *
 * @category  Unityreports
 * @package   Intelivemetrics_Unityreports
 * @copyright Copyright (c) 2014 Intelive Metrics Srl
 * @author    Eduard Gabriel Dumitrescu (balaur@gmail.com)
 */


class Intelivemetrics_Unityreports_Model_Request_Base {

    public function stopSync() {
        Mage::getConfig()->saveConfig(Intelivemetrics_Unityreports_Model_Config::XML_GENERAL_STATUS, 0);
        Mage::getConfig()->reinit();
        Mage::app()->reinitStores();
    }

    public function startSync() {
        Mage::getConfig()->saveConfig(Intelivemetrics_Unityreports_Model_Config::XML_GENERAL_STATUS, 1);
        Mage::getConfig()->reinit();
        Mage::app()->reinitStores();
        
    }

}

?>
