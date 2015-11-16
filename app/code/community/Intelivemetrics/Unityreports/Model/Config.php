<?php

/**
 * Config class
 *
 * @category  Unityreports
 * @package   Intelivemetrics_Unityreports
 * @copyright Copyright (c) 2014 Intelive Metrics Srl
 * @author    Eduard Gabriel Dumitrescu (balaur@gmail.com)
 */


class Intelivemetrics_Unityreports_Model_Config extends Mage_Core_Model_Abstract {

    const XML_GENERAL_STATUS = 'unityreports/general/status';
    
    //fallback for missing config key
    const MAX_ITEMS_PER_SYNC = 50;
    
    //Log file name
    const LOGFILE = 'unityreports.log';
    
    //CRON names
    const CRON_SYNC = 'unityreports_sync';
    const CRON_STAT = 'unityreports_stat';
    const CRON_COUNT = 'unityreports_count';
    const CRON_GLOBAL_COUNTERS = 'unityreports_globalcounters';

    protected function _construct() {
        $this->_init('unityreports/config');
    }

   
}