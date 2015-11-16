<?php

/**
 * Syncs data on product counts (views,add to carts, unique views)
 *
 * @category  Unityreports
 * @package   Intelivemetrics_Unityreports
 * @copyright Copyright (c) 2014 Intelive Metrics Srl
 * @author    Eduard Gabriel Dumitrescu (balaur@gmail.com)
 */
class Intelivemetrics_Unityreports_Model_Cron_Count extends Intelivemetrics_Unityreports_Model_Cron {

    /**
     * Called with magento cron
     * @return boolean
     * @assert () == true
     */
    public function runSync() {
        $helper = Mage::helper('unityreports');
        $helper->debug('*******NEW PRODUCT COUNTERS*******');
        try {
            //Check app status before syncing
            if (!$this->_appIsOk()) {
                $helper->debug('Endpoint is not receiving');
                return false;
            }

            //get a soap client
            $client = $this->_getClient();

            //get token
            $responseToken = json_decode($client->getToken(
                            $helper->getApiKey(), $helper->getApiSecret(), $helper->getLicenseKey()
            ));
            if ($responseToken->code != 'OK') {
                $helper->debug('Cannot get a valid Token.' . $responseToken->msg);
                return false;
            }

            //send data
            $data = $this->_getData();
            $blob = Intelivemetrics_Unityreports_Model_Utils::prepareDataForSending($data);
            $client->post(
                    $responseToken->msg, array(
                'type' => 'COUNT',
                'data' => $blob,
                'license' => $helper->getLicenseKey()
                    )
            );
            $helper->debug('Ok - sent '.count($data['products']['data']).' counters');
            
            //update last_sent_at
            $prodCountersTbl = Intelivemetrics_Unityreports_Model_Utils::getTableName('unityreports/product_counters');
            $query = "UPDATE $prodCountersTbl SET last_sent_at=NOW() WHERE last_updated_at>=last_sent_at OR last_sent_at IS NULL";
            Mage::getSingleton('unityreports/utils')->getDb()->query($query);
            Mage::getSingleton('unityreports/utils')->getDb()->closeConnection();
            return true;
        } catch (Exception $e) {
            $helper->debug($e, Zend_Log::ERR);
            $helper->debug('FILE: ' . __FILE__ . 'LINE: ' . __LINE__);
            return false;
        }
    }

    /**
     * Get all counters data
     * @return array associativo contenente i dati
     */
    protected function _getData() {
        $helper = Mage::helper('unityreports');
        try {
            //get product counters
            $prodCountersTbl = Intelivemetrics_Unityreports_Model_Utils::getTableName('unityreports/product_counters');
            $query = "SELECT *FROM $prodCountersTbl WHERE last_updated_at>=last_sent_at OR last_sent_at IS NULL";
            $res = Mage::getSingleton('unityreports/utils')->getDb()->query($query);
            $i = 0;
            $data = null;
            while ($fields = $res->fetch()) {
                //add column definitions
                if ($i == 0) {
                    foreach ($fields as $key => $val) {
                        $data['products']['columns'][] = $key;
                    }
                }
                //add data
                foreach ($fields as $key => $val) {
                    $data['products']['data'][$i][] = $val;
                }
                $i++;
            }
            $data['products']['counters_date'] = date('Y-m-d H:i:s');

            //TODO: get customers counter

            Mage::getSingleton('unityreports/utils')->getDb()->closeConnection();
            return $data;
        } catch (Exception $ex) {
            $helper->debug($ex, Zend_Log::ERR);
            $helper->debug('FILE: ' . __FILE__ . 'LINE: ' . __LINE__);
            return null;
        }
    }

}

?>
